<?php

class Vindi_Settings
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

    public function get_token()
    {
        return $this->token;
    }
}
