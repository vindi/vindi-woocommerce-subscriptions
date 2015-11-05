<?php

class Vindi_BankSlip_Gateway extends Vindi_Base_Gateway
{
    public function __construct(Vindi_Settings $container)
    {
        $this->id           = 'vindi-bank-slip';
        $this->method_title = __('Vindi - Boleto Bancário', 'woocommerce-vindi');
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
        return 'yes' === $this->enabled
            && 'BR' === $this->get_country_code()
            && $this->container->api->accept_bank_slip()
            && $this->container->check_ssl();
    }

    /**
     * Payment fields for Vindi Direct Checkout
     * @return void
     */
    public function payment_fields()
    {
        $user_country = $this->get_country_code();

        if (empty($user_country)) {
            _e('Selecione o País para visualizar as formas de pagamento.', 'woocommerce-vindi');
            return;
        }

        if ($user_country != 'BR') {
            _e('Vindi não está disponível no seu País.', 'woocommerce-vindi');
            return;
        }

        if (! $this->api->accept_bank_slip()) {
            _e('Este método de pagamento não é aceito.', 'woocommerce-vindi');
            return;
        }

        $$is_trial = $this->container->api->is_merchant_status_trial();
        $this->get_template('html-bankslip-checkout.php', $is_trial);
    }

    /**
     * Display download button for invoice.
     *
     * @param int $order_id
     */
    public function thank_you_page($order_id)
    {
        if ($download_url = get_post_meta($order_id, 'vindi_wc_invoice_download_url', true)) {
            $this->get_template('html-bankslip-download.php', $download_url);
        }
    }

    /**
     * Initialize Gateway Settings Form Fields
     * @return void
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled'         => array(
                'title'       => __('Habilitar/Desabilitar', 'woocommerce-vindi'),
                'label'       => __('Habilitar pagamento por Boleto Bancário com Vindi', 'woocommerce-vindi'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ),
            'title'           => array(
                'title'       => __('Título', 'woocommerce-vindi'),
                'type'        => 'text',
                'description' => __('Título que o cliente verá durante o processo de pagamento.', 'woocommerce-vindi'),
                'default'     => __('Boleto Bancário', 'woocommerce-vindi'),
            )
        );
    }
}
