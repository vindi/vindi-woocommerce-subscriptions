<?php

if ( ! function_exists( 'get_plugins' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

class Vindi_Dependencies
{
    /**
     * @var array
     **/
    private static $active_plugins;

    /**
    * Init Vindi_Dependencies.
    */
    public static function init()
    {
        self::$active_plugins = (array) get_option('active_plugins', array());

        if (is_multisite())
            self::$active_plugins = array_merge(self::$active_plugins, get_site_option('active_sitewide_plugins', array()));
    }

    /**
    * @return  boolean
    */
    public static function check()
    {
        if (! self::$active_plugins)
            self::init();
        $required_plugins = [
            [
                'path'      => 'woocommerce/woocommerce.php',
                'plugin'    => [
                    'name'      => 'WooCommerce',
                    'url'       => 'https://wordpress.org/extend/plugins/woocommerce/',
                    'version'   => [
                        'validation'    => '>=',
                        'number'        => '3.0'
                    ]
                ]
            ],
            [
                'path'      => 'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php',
                'plugin'    => [
                    'name'      => 'WooCommerce Extra Checkout Fields for Brazil',
                    'url'       => 'https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/',
                    'version'   => [
                        'validation'    => '>=',
                        'number'        => '3.5'
                    ]
                ]
            ]
        ];

        self::wc_subscriptions_are_activated();

        foreach($required_plugins as $plugin) {
            if(self::plugin_are_active($plugin) == false) {
                self::missing_notice($plugin['plugin']['name'],
                    $plugin['plugin']['version']['number'],
                    $plugin['plugin']['url']);
                return false;
            }

            if(self::verify_version_of_plugin($plugin) == false)
                return false;
        }

        return true;
    }

    /**
    * @param string $name
    * @param string $link
    *
    * @return  string
    */
    public static function missing_notice($name, $version, $link)
    {
        echo '<div class="error"><p>' . sprintf(__('O  Plugin Vindi WooCommerce depende da versão %s do %s para funcionar!', VINDI_IDENTIFIER), $version, "<a href=\"{$link}\">" . __($name, VINDI_IDENTIFIER) . '</a>') . '</p></div>';
    }

    /**
    * @param array plugin
    *
    * @return boolean
    **/
    public static function plugin_are_active($plugin)
    {
        if(in_array($plugin['path'], self::$active_plugins))
            return true;

        return  false;
    }

    /**
    * @param array plugins
    *
    * @return boolean
    **/
    public static function verify_version_of_plugin($plugin)
    {
        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . "../" . $plugin['path']);
        $version_match = $plugin['plugin']['version'];
        $version_compare = version_compare(
                $plugin_data['Version'],
                $version_match['number'],
                $version_match['validation']
            );

        if($version_compare == false){
            add_action(
                'admin_notices',
                self::missing_notice($plugin['plugin']['name'],
                    $version_match['number'],
                    $plugin['plugin']['url'])
            );

            return false;
        }

        return true;
    }

    /**
    * @return boolean
    **/
    public static function wc_subscriptions_are_activated()
    {
        $wc_subscriptions = [
            'path'      => 'woocommerce-subscriptions/woocommerce-subscriptions.php',
            'plugin'    => [
               'name'       => 'WooCommerce Subscriptions',
               'url'        => 'http://www.woothemes.com/products/woocommerce-subscriptions/',
               'version'    => [
                    'validation'    => '>=',
                    'number'        => '2.2'
                ]
            ],
        ];

        return self::plugin_are_active($wc_subscriptions) || class_exists('WC_Subscriptions');
    }

    /**
    * @return  boolean
    */
    public static function wc_memberships_are_activated()
    {
        $wc_memberships = [
            'path'      => 'woocommerce-memberships/woocommerce-memberships.php',
            'plugin'    => [
                'name'  => 'WooCommerce Memberships',
                'url'   => 'http://www.woothemes.com/products/woocommerce-memberships/'
            ]
        ];
        if(self::plugin_are_active($wc_memberships)) {
            return true;
        }
        return false;
    }
}
