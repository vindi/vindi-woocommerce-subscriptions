<?php

class Vindi_Settings extends WC_Settings_API
{
    /**
     * @var Vindi_Dependencies
     **/
    public $dependency;

    /**
     * @var Vindi_WooCommerce_Subscriptions
     **/
    private $plugin;

    /**
     * @var string
     **/
    private $token;

    /**
     * @var boolean
     **/
    private $debug;

    /**
     * @var Vindi_API
     **/
    public $api;

    /**
     * @var Vindi_Logger
     **/
    public $logger;

    /**
     * @var WooCommerce
     **/
    public $woocommerce;


    public function __construct()
    {
        global $woocommerce;

        $this->token = sanitize_file_name(wp_hash(VINDI_IDENTIFIER));

        $this->init_form_fields();
        $this->init_settings();

        $this->debug       = $this->get_option('debug') == 'yes' ? true : false;
        $this->logger      = new Vindi_Logger(VINDI_IDENTIFIER, $this->debug);
        $this->api         = new Vindi_API($this->get_api_key(), $this->logger, $this->get_is_active_sandbox());
        $this->dependency  = new Vindi_Dependencies;
        $this->woocommerce = $woocommerce;

        add_filter('woocommerce_payment_gateways', array(&$this, 'add_gateway'));

        if ($this->dependency->wc_subscriptions_are_activated()){
            add_action('admin_notices', array(&$this, 'manual_renew_is_deactivated'));
            add_action('admin_notices', array(&$this, 'allow_switching_is_activated'));
        }

        if(is_admin()) {
            add_filter('woocommerce_settings_tabs_array', array(&$this, 'add_settings_tab'), 50);
            add_action('woocommerce_settings_tabs_settings_vindi', array(&$this, 'settings_tab'));
            add_action('woocommerce_update_options_settings_vindi', array(&$this, 'process_admin_options'));
        }
    }

    /**
     * Create settings tab
     */
    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['settings_vindi'] = __('Vindi', VINDI_IDENTIFIER);
        return $settings_tabs;
    }

    /**
     * Include Settings View
     */
    public function settings_tab()
    {
        $this->get_template('admin-settings.html.php', array('settings' => $this));
    }

    /**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields()
    {
		$url           = admin_url(sprintf('admin.php?page=wc-status&tab=logs&log_file=%s-%s-log', VINDI_IDENTIFIER, $this->get_token()));
		$logs_url      = '<a href="' . $url . '" target="_blank">' . __('Ver Logs', VINDI_IDENTIFIER) . '</a>';
		$nfe_know_more = '<a href="http://atendimento.vindi.com.br/hc/pt-br/articles/204450944-Notas-fiscais" target="_blank">' . __('Saiba mais', VINDI_IDENTIFIER) . '</a>';

		$prospects_url = '<a href="https://app.vindi.com.br/prospects/new" target="_blank">' . __('Não possui uma conta?', VINDI_IDENTIFIER) . '</a>';

        $sand_box_article = '<a href="https://atendimento.vindi.com.br/hc/pt-br/articles/115012242388-Sandbox" target="_blank">' . __('Dúvidas?', VINDI_IDENTIFIER) . '</a>';

		$this->form_fields = array(
			'api_key'              => array(
				'title'            => __('Chave da API Vindi', VINDI_IDENTIFIER),
				'type'             => 'text',
				'description'      => __('A Chave da API de sua conta na Vindi. ' . $prospects_url, VINDI_IDENTIFIER),
				'default'          => '',
            ),
			'send_nfe_information' => array(
				'title'            => __('Emissão de NFe\'s', VINDI_IDENTIFIER),
				'label'            => __('Enviar informações para emissão de NFe\'s', VINDI_IDENTIFIER),
				'type'             => 'checkbox',
				'description'      => sprintf(__('Envia informações de RG e Inscrição Estadual para Emissão de NFe\'s com nossos parceiros. %s', VINDI_IDENTIFIER), $nfe_know_more),
				'default'          => 'no',
			),
            'discounts_to_cycles' => array(
                'title'       => __('Número de ciclos dos cupons de desconto', VINDI_IDENTIFIER),
                'type'        => 'select',
                'description' => __('Número de ciclos que os cupons de desconto serão aplicados nas assinaturas.', VINDI_IDENTIFIER),
                'default'     => '0',
                'options'     => array(
                    '-1' => 'Ciclos do cupom',
                    '0'  => 'Todos os ciclos',
                    '1'  => '1 ciclo',
                    '2'  => '2 ciclos',
                    '3'  => '3 ciclos',
                    '4'  => '4 ciclos',
                    '5'  => '5 ciclos',
                    '6'  => '6 ciclos',
                    '7'  => '7 ciclos',
                    '8'  => '8 ciclos',
                    '9'  => '9 ciclos',
                    '10' => '10 ciclos',
                    '11' => '11 ciclos',
                    '12' => '12 ciclos',
                ),
			),
			'return_status'        => array(
				'title'            => __('Status de conclusão do pedido', VINDI_IDENTIFIER),
				'type'             => 'select',
				'description'      => __('Status que o pedido deverá ter após receber a confirmação de pagamento da Vindi.', VINDI_IDENTIFIER),
				'default'          => 'processing',
				'options'          => array(
					'processing'   => 'Processando',
					'on-hold'      => 'Aguardando',
					'completed'    => 'Concluído',
				),
			),
            'vindi_synchronism'        => array(
                'title'            => __('Sincronismo de Status das Assinaturas', VINDI_IDENTIFIER),
                'type'             => 'checkbox',
                'label'      => __('Enviar alterações de status nas assinaturas do WooCommerce', VINDI_IDENTIFIER),
                'description'      => __('Envia as alterações de status nas assinaturas do WooCommerce para Vindi.', VINDI_IDENTIFIER),
                'default'          => 'no',
            ),
            'shipping_and_tax_config'  => array(
                'title'            => __('Cobrança única', VINDI_IDENTIFIER),
                'type'             => 'checkbox',
                'label'      => __('Ativar cobrança única para fretes e taxas', VINDI_IDENTIFIER),
                'description'      => __('Fretes e Taxas serão cobrados somente no primeiro ciclo de uma assinatura', VINDI_IDENTIFIER),
                'default'          => 'no',
            ),
			'testing'              => array(
				'title'            => __('Testes', 'vindi-woocommerce'),
				'type'             => 'title',
			),
            'sandbox'             => array(
                'title'            => __('Ambiente Sandbox', VINDI_IDENTIFIER),
                'label'            => __('Ativar Sandbox', VINDI_IDENTIFIER),
                'type'             => 'checkbox',
                'description'      => __('Ative esta opção para habilitar a comunicação com o ambiente Sandbox da Vindi.', VINDI_IDENTIFIER),
                'default'          => 'no',
            ),
            'api_key_sandbox'     => array(
                'title'            => __('Chave da API Sandbox Vindi', VINDI_IDENTIFIER),
                'type'             => 'text',
                'description'      => __('A Chave da API Sandbox de sua conta na Vindi (só preencha se a opção anterior estiver habilitada). ' . $sand_box_article, VINDI_IDENTIFIER),
                'default'          => '',
            ),
			'debug'                => array(
				'title'            => __('Log de Depuração', VINDI_IDENTIFIER),
				'label'            => __('Ativar Logs', VINDI_IDENTIFIER),
				'type'             => 'checkbox',
				'description'      => sprintf(__('Ative esta opção para habilitar logs de depuração do servidor. %s', VINDI_IDENTIFIER), $logs_url),
				'default'          => 'no',
			),
		);
	}

    /**
     * Get Uniq Token Access
     *
     * @return string
     **/
    public function get_token()
    {
        return $this->token;
    }

    /**
     * Get Vindi API Key
     * @return string
     **/
    public function get_api_key()
    {
        if('yes' === $this->get_is_active_sandbox()) {
            return $this->settings['api_key_sandbox'];
        }

        return $this->settings['api_key'];
    }

    /**
     * Get Return Status
     * @return string
     **/
    public function get_return_status()
    {
        if(isset($this->settings['return_status'])) {
            return $this->settings['return_status'];
        } else {
            return 'processing';
        }
    }

    /**
     * Get Vindi API Key
     * @return string
     **/
    public function send_nfe_information()
    {
        return 'yes' === $this->settings['send_nfe_information'];
    }

    /**
     * Get Vindi Synchronism status
     * @return string
     **/
    public function get_synchronism_status()
    {
        return 'yes' === $this->settings['vindi_synchronism'];
    }

    /**
     * Get Vindi Shipping and Tax config
     * @return string
     **/
    public function get_shipping_and_tax_config()
    {
        return 'yes' === $this->settings['shipping_and_tax_config'];
    }

    /**
     * @return int
     **/
    public function cycles_to_discount()
    {
        if(!empty($this->settings['discounts_to_cycles'])) {
            return (int) $this->settings['discounts_to_cycles'];
        }

        return null;
    }

    /**
     * Return
     * @return boolean
     **/
    public function get_is_active_sandbox()
    {
        return $this->settings['sandbox'];
    }

    /**
	 * Return the URL that will receive the webhooks.
	 * @return string
	 */
	public function get_events_url() {
		return sprintf('%s/index.php/wc-api/%s?token=%s',
            get_site_url(),
            Vindi_WooCommerce_Subscriptions::WC_API_CALLBACK,
            $this->get_token()
        );
	}

    /**
     * Check if SSL is enabled when merchant is not trial.
     * @return boolean
     */
    public function check_ssl()
    {
        return $this->api->is_merchant_status_trial_or_sandbox()
            || is_ssl();
    }

    /**
     * Add the gateway to WooCommerce.
     *
     * @param array     $methods WooCommerce payment methods.
     *
     * @return array    Payment methods with Vindi.
     */
    public function add_gateway($methods)
    {
    	$methods[] = new Vindi_BankSlip_Gateway($this);
    	$methods[] = new Vindi_CreditCard_Gateway($this);

    	return $methods;
    }

    /**
     * WC Get Template helper.
     *
     * @param string    $name
     * @param array     $args
     */
    public function get_template($name, $args = array())
    {
        wc_get_template(
            $name,
            $args,
            '',
            sprintf('%s/../%s',
                dirname(__FILE__),
                Vindi_WooCommerce_Subscriptions::VIEWS_DIR
            )
        );
    }

    /**
     * Add a script in the wordpress script queue
     * @param string    $path
     * @param array     $dependencies
     **/
    public function add_script($path, $dependencies=array())
    {
        wp_enqueue_script(
            'vindi-checkout',
            Vindi_WooCommerce_Subscriptions::generate_assets_url($path),
            $dependencies
        );
    }

    /**
     * Warning if manual renew is not activated
     **/
    public function manual_renew_is_deactivated()
    {
        if('yes' === get_option('woocommerce_subscriptions_turn_off_automatic_payments') &&
           'yes' === get_option('woocommerce_subscriptions_accept_manual_renewals'))
            return ;

        $this->get_template('manual_renew_is_deactivated.html.php');
    }

    public function allow_switching_is_activated()
    {
        if('no' === get_option('woocommerce_subscriptions_allow_switching'))
            return ;

        $this->get_template('allow_switching_is_activated.html.php');
    }

    /**
     * Validate API key field
     * @param string $text
     * @return string $text
     */
    public function validate_api_key_field($key)
    {
        $api_key = $this->get_option($key);

        if (isset($_POST[$this->plugin_id . $this->id . '_' . $key]) AND !empty($_POST[$this->plugin_id . $this->id . '_' . $key])) {
            $api_key = wp_kses_post( trim( stripslashes($_POST[ $this->plugin_id . $this->id . '_' . $key])));
            if('unauthorized' === $this->api->test_api_key($api_key))
                $api_key = '';
        }

        return $api_key;
    }
}
