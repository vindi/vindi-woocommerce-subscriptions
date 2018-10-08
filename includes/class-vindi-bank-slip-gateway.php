<?php

class Vindi_BankSlip_Gateway extends Vindi_Base_Gateway
{
    public function __construct(Vindi_Settings $container)
    {
        $this->id           = 'vindi-bank-slip';
        $this->method_title = __('Vindi - Boleto Bancário', VINDI_IDENTIFIER);
        $this->has_fields   = true;

        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_thankyou_' . $this->id, array(&$this, 'thank_you_page'));

        parent::__construct($container);
    }

    /**
     * Should return payment type for payment processing.
     * @return string
     */
    public function type()
    {
        return 'invoice';
    }

    /**
     * Check if this gateway is enabled and available in the user's country
     * @return bool
     */
    public function is_available()
    {
        if(false === is_checkout())
            return false;

        return 'yes' === $this->enabled
            && $this->container->api->accept_bank_slip()
            && $this->container->check_ssl();
    }

    /**
     * Payment fields for Vindi Direct Checkout
     */
    public function payment_fields()
    {
        $user_country = $this->get_country_code();

        if (empty($user_country)) {
            _e('Selecione o País para visualizar as formas de pagamento.', VINDI_IDENTIFIER);
            return;
        }

        if (! $this->container->api->accept_bank_slip()) {
            _e('Este método de pagamento não é aceito.', VINDI_IDENTIFIER);
            return;
        }

        $is_single_order = $this->is_single_order();

        if ($is_trial = $this->container->get_is_active_sandbox())
            $is_trial = $this->container->api->is_merchant_status_trial_or_sandbox();
        
        $this->container->get_template('bankslip-checkout.html.php', compact('is_trial', 'is_single_order'));
    }

    /**
     * Display download button for invoice.
     *
     * @param int $order_id
     */
    public function thank_you_page($order_id)
    {
        if ($download_url = get_post_meta($order_id, 'vindi_wc_invoice_download_url', true)) {
            $this->container->get_template('bankslip-download.html.php', compact('download_url'));
        }
    }

    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled'         => array(
                'title'       => __('Habilitar/Desabilitar', VINDI_IDENTIFIER),
                'label'       => __('Habilitar pagamento por Boleto Bancário com Vindi', VINDI_IDENTIFIER),
                'type'        => 'checkbox',
                'default'     => 'no',
            ),
            'title'           => array(
                'title'       => __('Título', VINDI_IDENTIFIER),
                'type'        => 'text',
                'description' => __('Título que o cliente verá durante o processo de pagamento.', VINDI_IDENTIFIER),
                'default'     => __('Boleto Bancário', VINDI_IDENTIFIER),
            )
        );
    }
}
