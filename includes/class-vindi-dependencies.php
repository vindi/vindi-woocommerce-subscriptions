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
            'woocommerce/woocommerce.php' => [
                'WooCommerce' => 'https://wordpress.org/extend/plugins/woocommerce/',
                'version'     => ['>=', '3.0']
            ],
            'woocommerce-subscriptions/woocommerce-subscriptions.php' => [
                'WooCommerce Subscriptions' => 'http://www.woothemes.com/products/woocommerce-subscriptions/',
                'version'     => ['>=', '2.2']
            ],
            'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php' => [
                'WooCommerce Extra Checkout Fields for Brazil' => 'https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/',
                'version'     => ['>=', '3.5']
            ]
        ];

        if (! self::plugins_are_activated($required_plugins))
            return false;

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
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Vindi Subscriptions depende da versão %s do %s para funcionar!', VINDI_IDENTIFIER), $version, "<a href=\"{$link}\">" . __($name, VINDI_IDENTIFIER) . '</a>') . '</p></div>';
    }

    /**
    * @return  boolean
    */
    public function memberships_are_activated()
    {
        $memberships = [
            'woocommerce-memberships/woocommerce-memberships.php' => [
                'WooCommerce Memberships' => 'http://www.woothemes.com/products/woocommerce-memberships/'
            ]
        ];

        if(self::plugins_are_activated($memberships)) {
            return true;
        }

        return false;
    }

    /**
    * @param array $plugin
    *
    * @return boolean
    **/
    public static function plugins_are_activated($plugins)
    {
        foreach($plugins as $path => $plugin) {
            $plugin_data   = get_plugin_data(ABSPATH . "wp-content/plugins/" . $path);
            $version_match = $plugin['version'];

            if(!in_array($path, self::$active_plugins ) && !array_key_exists($path, self::$active_plugins)) {
                add_action('admin_notices', self::missing_notice(key($plugin), $version_match[1], current($plugin)));
                return false;
            }

            if(empty($plugin['version'])) {
              return true;
            }

            if(!version_compare( $plugin_data['Version'], $version_match[1], $version_match[0] )) {
                add_action('admin_notices', self::missing_notice(key($plugin), $version_match[1], current($plugin)));
                return false;
            }
        }

        return true;
    }
}
