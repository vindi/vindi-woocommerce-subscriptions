<?php

class Vindi_CreditCard_Gateway extends Vindi_Base_Gateway
{
    /**
     * @var int
     */
    private $max_installments = 12;

    public function __construct(Vindi_Settings $container)
    {
        $this->id           = 'vindi-wc-creditcard';
        $this->method_title = __('Vindi - Cartão de Crédito', VINDI_IDENTIFIER);
        $this->has_fields   = true;

        $this->init_form_fields();
        $this->init_settings();

        $this->smallest_installment = $this->get_option('smallest_installment');
        $this->installments         = $this->get_option('installments');

        parent::__construct($container);

        add_action('wp_enqueue_scripts', array(&$this, 'checkout_script'));
    }

    /**
     * Should return payment type for payment processing.
     * @return string
     */
    public function type()
    {
        return 'cc';
    }

    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Habilitar/Desabilitar', VINDI_IDENTIFIER),
                'label'   => __('Habilitar pagamento via Cartão de Crédito com a Vindi', VINDI_IDENTIFIER),
                'type'    => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __('Título', VINDI_IDENTIFIER),
                'type'        => 'text',
                'description' => __('Título que o cliente verá durante o processo de pagamento.', VINDI_IDENTIFIER),
                'default'     => __('Cartão de Crédito', VINDI_IDENTIFIER),
            ),
            'single_charge' => array(
                'title' => __('Vendas Avulsas', VINDI_IDENTIFIER),
                'type'  => 'title',
            ),
            'smallest_installment' => array(
                'title'       => __('Valor mínimo da parcela', VINDI_IDENTIFIER),
                'type'        => 'text',
                'description' => __('Valor mínimo da parcela, não deve ser inferior a R$ 5,00.', VINDI_IDENTIFIER),
                'default'     => '5',
            ),
            'installments' => array(
                'title'       => __('Número máximo de parcelas', VINDI_IDENTIFIER),
                'type'        => 'select',
                'description' => __('Número máximo de parcelas para vendas avulsas. Deixe em 1x para desativar o parcelamento.', VINDI_IDENTIFIER),
                'default'     => '1',
                'options'     => array(
                    '1'  => '1x',
                    '2'  => '2x',
                    '3'  => '3x',
                    '4'  => '4x',
                    '5'  => '5x',
                    '6'  => '6x',
                    '7'  => '7x',
                    '8'  => '8x',
                    '9'  => '9x',
                    '10' => '10x',
                    '11' => '11x',
                    '12' => '12x',
                ),
            )
        );
    }

    /**
     * Check if this gateway is enabled and available in the user's country
     * @return bool
    */
    public function is_available()
    {
        if(false === is_checkout())
            return false;

        $methods    = $this->container->api->get_payment_methods();
        $cc_methods = $methods['credit_card'];

        return 'yes' === $this->enabled
            && 'BR' === $this->get_country_code()
            && count($cc_methods)
            && $this->container->check_ssl();
    }

    /**
     * Payment fields for Vindi Direct Checkout
     */
    public function payment_fields()
    {
        if ($this->is_single_order() && $this->installments > 1) {

            $total = $this->container->woocommerce->cart->total;
            $installments = '';

            for ( $i = 1; $i <= $this->installments; $i ++ ) {
                $value = ceil( $total / $i * 100 ) / 100;
                if ($value >= $this->smallest_installment) {
                    $price = wc_price($value);
                    $installments .= '<option value="' . $i . '">' . sprintf(__('%dx de %s', VINDI_IDENTIFIER), $i, $price) . '</option>';
                } else {
                    $this->max_installments = $i - 1;
                    break;
                }
            }
        }

        $user_country = $this->get_country_code();

        if (empty($user_country)) {
            _e( 'Selecione o País para visualizar as formas de pagamento.', VINDI_IDENTIFIER);
            return;
        }

        if ($user_country != 'BR') {
            _e('Vindi não está disponível no seu País.', VINDI_IDENTIFIER);
            return;
        }

        $payment_methods = $this->container->api->get_payment_methods();

        if ($payment_methods === false || empty($payment_methods) || ! count($payment_methods['credit_card'])) {
            _e( 'Estamos enfrentando problemas técnicos no momento. Tente novamente mais tarde ou entre em contato.', VINDI_IDENTIFIER);
            return;
        }

        //@TODO create a element into a view
        $months = '<option value="">' . __('Mês', VINDI_IDENTIFIER) . '</option>';

        for ($i = 1 ; $i <= 12 ; $i++) {
            $timestamp = mktime( 0, 0, 0, $i, 1);
            $num       = date('m', $timestamp);
            $name      = date('F', $timestamp);
            $months   .= sprintf('<option value="%s">%02d - %s</option>', $num, $num, __($name));
        }

        $years = '<option value="">' . __('Ano', VINDI_IDENTIFIER) . '</option>';

        for ( $i = date( 'Y' ); $i <= date( 'Y' ) + 15; $i ++ ) {
            $years .= sprintf( '<option value="%u">%u</option>', $i, $i );
        }

        $is_trial = $this->container->api->is_merchant_status_trial();

        $this->container->get_template('creditcard-checkout.html.php', compact('months', 'years', 'installments', 'is_trial'));
    }

    /**
     * Validate payment fields
     */
    public function validate_fields()
    {
        $fields = array(
            'vindi_cc_fullname'    => __('Nome do Portador do Cartão de Crédito requerido.', VINDI_IDENTIFIER),
            'vindi_cc_number'      => __('Número do Cartão de Crédito requerido.', VINDI_IDENTIFIER),
            'vindi_cc_cvc'         => __('Código de Segurança do Cartão requerido.', VINDI_IDENTIFIER),
            'vindi_cc_monthexpiry' => __('Mês de Validade do Cartão requerido.', VINDI_IDENTIFIER),
            'vindi_cc_yearexpiry'  => __('Ano de Validade do Cartão requerido.', VINDI_IDENTIFIER),
        );

        foreach ( $fields as $field => $message ) {
            if (! isset($_POST[$field]) || empty($_POST[$field]))
                wc_add_notice( $message, 'error' );
        }

        /* Validate expiry date */
        $now      = time();
        $ccExpiry = mktime(0, 0, 0, (int) $_POST['vindi_cc_monthexpiry'], 1, (int) $_POST['vindi_cc_yearexpiry']);
        if ($now > $ccExpiry)
            wc_add_notice(__('Este cartão de crédito já expirou. Tente novamente com outro cartão de crédito dentro do prazo de validade.', VINDI_IDENTIFIER ), 'error');

        if ($this->is_single_order() && $this->installments > 1) {
            if (! isset($_POST['vindi_cc_installments']) || empty($_POST['vindi_cc_installments']))
                wc_add_notice(__('Quantidade de Parcelas requerido.', VINDI_IDENTIFIER ), 'error');

            if (1 > $_POST['vindi_cc_installments'] || $this->max_installments < $_POST['vindi_cc_installments'])
                wc_add_notice(__('A Quantidade de Parcelas escolhidas é inválida.', VINDI_IDENTIFIER), 'error');
        }

        $this->validated = ! wc_notice_count();
    }

    /**
     * Checkout scripts
     */
    public function checkout_script()
    {
        if (! (get_query_var('order-received')) && is_checkout())
            $this->container->add_script('js/checkout.js', array('jquery', 'jquery-payment'));
    }
}
