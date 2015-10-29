<?php

class Vindi_Settings extends WC_Settings_API
{
    /**
     * @var Vindi_WooCommerce_Subscriptions
     **/
    private $plugin;

    /**
     * @var string
     **/
    private $api_key;

    /**
     * @var string
     **/
    private $token;

    public function __construct(Vindi_WooCommerce_Subscriptions $plugin)
    {
        $this->plugin = $plugin;
        $this->token = sanitize_file_name(wp_hash( 'vindi-wc' ));
    }

    public function init()
    {
        $this->init_form_fields();
        add_filter( 'woocommerce_settings_tabs_array', [&$this, 'add_settings_tab'], 50);
        add_action( 'woocommerce_settings_tabs_settings_vindi', [&$this, 'settings_tab']);
    }

    public static function add_settings_tab( $settings_tabs )
    {
        $settings_tabs['settings_vindi'] = __( 'Vindi', VINDI_IDENTIFIER);
        return $settings_tabs;
    }


    public function settings_tab()
    {
        include_once(sprintf('%s/%s', Vindi_WooCommerce_Subscriptions::VIEWS_DIR, 'admin-settings.html.php'));
    }

    /**
	 * Initialize Gateway Settings Form Fields
	 * @return void
	 */
	public function init_form_fields()
    {
		$url           = admin_url( 'admin.php?page=wc-status&tab=logs&log_file=vindi-wc-' . $this->get_token() . '-log' );
		$logs_url      = '<a href="' . $url . '" target="_blank">' . __( 'Ver Logs', 'woocommerce-vindi' ) . '</a>';
		$nfe_know_more = '<a href="http://atendimento.vindi.com.br/hc/pt-br/articles/204450944-Notas-fiscais" target="_blank">' . __( 'Saiba mais', 'woocommerce-vindi' ) . '</a>';

		$prospects_url = '<a href="https://app.vindi.com.br/prospects/new" target="_blank">' . __( 'Não possui uma conta?', 'woocommerce-vindi' ) . '</a>';

		$this->form_fields = [
			'api_key'         => [
				'title'       => __( 'Chave da API Vindi', 'woocommerce-vindi' ),
				'type'        => 'text',
				'description' => __( 'A Chave da API de sua conta na Vindi. ' . $prospects_url, 'woocommerce-vindi' ),
				'default'     => '',
			],
			'sendNfeInformation' => [
				'title'       => __( 'Emissão de NFe\'s', 'woocommerce-vindi' ),
				'label'       => __( 'Enviar informações para emissão de NFe\'s', 'woocommerce-vindi' ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'Envia informações de RG e Inscrição Estadual para Emissão de NFe\'s com nossos parceiros. %s', 'woocommerce-vindi' ), $nfe_know_more ),
				'default'     => 'no',
			],
			'returnStatus'       => [
				'title'       => __( 'Status de conclusão do pedido', 'woocommerce-vindi' ),
				'type'        => 'select',
				'description' => __( 'Status que o pedido deverá ter após receber a confirmação de pagamento da Vindi.', 'woocommerce-vindi' ),
				'default'     => 'processing',
				'options'     => [
					'processing' => 'Processando',
					'on-hold'    => 'Aguardando',
					'completed'  => 'Concluído',
				],
			],
			'testing'            => [
				'title' => __( 'Testes', 'vindi-woocommerce' ),
				'type'  => 'title',
			],
			'debug'              => [
				'title'       => __( 'Log de Depuração', 'woocommerce-vindi' ),
				'label'       => __( 'Ativar Logs', 'woocommerce-vindi' ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'Ative esta opção para habilitar logs de depuração do servidor. %s', 'woocommerce-vindi' ), $logs_url ),
				'default'     => 'no',
			],
		];
	}

    public function get_token()
    {
        return $this->token;
    }
}
