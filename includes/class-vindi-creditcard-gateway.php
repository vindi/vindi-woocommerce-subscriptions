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
        $this->verify_method        = $this->get_option('verify_method');

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
            'verify_method' => array(
                'title'       => __('Transação de Verificação', VINDI_IDENTIFIER),
                'type'        => 'checkbox',
                'description' => __(' Realiza a transação de verificação em todos os novos pedidos. (Taxas adicionais por verificação poderão ser cobradas).', VINDI_IDENTIFIER),
                'default'     => 'no',
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
     * Check if this gateway is enabled and available in the user's checkout
     * @return bool
    */
    public function is_available()
    {
        if(false === is_checkout())
            return false;

        return 'yes' === $this->enabled && $this->container->check_ssl();
    }

    /**
     * Check if this gateway verify method is enabled
     * @return bool
    */
    public function verify_method()
    {
        return 'yes' === $this->verify_method;
    }

    /**
     * @param array $payment_profile
     **/
    private function build_user_payment_profile()
    {
        $user_payment_profile = array();
        $user_code            = get_user_meta(wp_get_current_user()->ID, 'vindi_user_code', true);
        $payment_profile = WC()->session->get('current_payment_profile'); 
        $current_customer = WC()->session->get('current_customer'); 

        if (!isset($payment_profile) || $current_customer['code'] != $user_code) {
            $payment_profile = $this->container->api->get_payment_profile($user_code);
        }

        if($payment_profile['type'] !== 'PaymentProfile::CreditCard')
            return $user_payment_profile;

        if(false === empty($payment_profile)) {
            $user_payment_profile['holder_name']     = $payment_profile['holder_name'];
            $user_payment_profile['payment_company'] = $payment_profile['payment_company']['code'];
            $user_payment_profile['card_number']     = sprintf('**** **** **** %s', $payment_profile['card_number_last_four']);
        }

        WC()->session->set('current_payment_profile', $payment_profile); 
        return $user_payment_profile;
    }

    /**
     * Payment fields for Vindi Direct Checkout
     */
    public function payment_fields()
    {
        $total      = $this->container->woocommerce->cart->total;
        $max_times  = $this->get_order_max_installments($total);

        if ($max_times > 1) {
            for ($times = 1; $times <= $max_times; $times++) {
                $installments[$times] = ceil($total / $times * 100) / 100;
            }
        }

        $user_payment_profile = $this->build_user_payment_profile();
        $payment_methods      = $this->container->api->get_payment_methods();

        if ($payment_methods === false || empty($payment_methods) || ! count($payment_methods['credit_card'])) {
            _e( 'Estamos enfrentando problemas técnicos no momento. Tente novamente mais tarde ou entre em contato.', VINDI_IDENTIFIER);
            return;
        }

        $months = array();

        for ($i = 1 ; $i <= 12 ; $i++) {
            $timestamp    = mktime( 0, 0, 0, $i, 1);
            $num          = date('m', $timestamp);
            $name         = date('F', $timestamp);
            $months[$num] = __($name);
        }

        $years = array();

        for ($i = date('Y') ; $i <= date('Y') + 15 ; $i++)
            $years[] = $i;

        if ($is_trial = $this->container->get_is_active_sandbox())
            $is_trial = $this->container->api->is_merchant_status_trial_or_sandbox();

        $this->container->get_template('creditcard-checkout.html.php', compact(
            'months',
            'years',
            'installments',
            'is_trial',
            'user_payment_profile',
            'payment_methods'
        ));
    }

    /**
     * get installments on bills and subscriptions
     */
    protected function get_installments()
    {
        if($this->is_single_order())
            return $this->installments;

        foreach($this->container->woocommerce->cart->cart_contents as $item) {
            $plan_id = $item['data']->get_meta('vindi_subscription_plan');
            if (!empty($plan_id))
                break;
        }
        
        $current_plan = WC()->session->get('current_plan');
        if ($current_plan && $current_plan['id'] == $plan_id && !empty($current_plan['installments']))
            return $current_plan['installments'];

        $plan = $this->container->api->get_plan($plan_id);
        WC()->session->set('current_plan', $plan);
        if($plan['installments'] > 1)
            return $plan['installments'];               
                
        return 1;
    }

    /**
     * get max installments on order
     */
    protected function get_order_max_installments($order_total)
    {
        if($this->is_single_order()) {
            $order_max_times = floor($order_total / $this->smallest_installment);
            $max_times       = empty($order_max_times) ? 1 : $order_max_times;

            return min($this->max_installments, $max_times, $this->get_installments());
        }
        return $this->get_installments();
    }

    /**
     * Validate payment fields
     */
    public function validate_fields()
    {
        if ($this->is_single_order() && $this->installments < 1) {
            if (! isset($_POST['vindi_cc_installments']) || empty($_POST['vindi_cc_installments'])) {
                wc_add_notice(__('Quantidade de Parcelas requerido.', VINDI_IDENTIFIER ), 'error');
            }

            $total = $this->container->woocommerce->cart->total;

            if ($_POST['vindi_cc_installments'] > $this->get_order_max_installments($total)) {
                wc_add_notice(__('A Quantidade de Parcelas escolhidas é inválida.', VINDI_IDENTIFIER), 'error');
            }
        }

        if($this->verify_user_payment_profile()) {
            $this->validated = ! wc_notice_count();
            return ;
        }

        $fields = array(
            'vindi_cc_fullname'         => __('Nome do Portador do Cartão de Crédito requerido.', VINDI_IDENTIFIER),
            'vindi_cc_paymentcompany'   => __('Bandeira do Cartão de Crédito requerido.', VINDI_IDENTIFIER),
            'vindi_cc_number'           => __('Número do Cartão de Crédito requerido.', VINDI_IDENTIFIER),
            'vindi_cc_cvc'              => __('Código de Segurança do Cartão requerido.', VINDI_IDENTIFIER),
            'vindi_cc_monthexpiry'      => __('Mês de Validade do Cartão requerido.', VINDI_IDENTIFIER),
            'vindi_cc_yearexpiry'       => __('Ano de Validade do Cartão requerido.', VINDI_IDENTIFIER),
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

    /**
     * verify if a previous payment profile was used
     **/
    public function verify_user_payment_profile()
    {
        $old_payment_profile = (int) filter_input(
            INPUT_POST,
            'vindi-old-cc-data-check',
            FILTER_SANITIZE_NUMBER_INT
        );

        return 1 === $old_payment_profile;
    }
}
