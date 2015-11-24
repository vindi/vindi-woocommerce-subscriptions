<?php

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
    * WooCommerce fallback notice.
    * @return  boolean
    */
    public static function check()
    {
        if (! self::$active_plugins) self::init();

        if (! self::is_woocommerce_activated()) {
            add_action('admin_notices', 'Vindi_Dependencies::woocommerce_missing_notice');
            return false;
        }

        if (! self::is_woocommerce_subscription_activated()) {
            add_action('admin_notices', 'Vindi_Dependencies::woocommerce_subscriptions_missing_notice');
            return false;
        }
    }

    /**
    * WooCommerce fallback notice.
    * @return  string
    */
    public static function woocommerce_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Vindi Gateway depende da última versão do %s para funcionar!', VINDI_IDENTIFIER), '<a href="https://wordpress.org/extend/plugins/woocommerce/">' . __('WooCommerce', VINDI_IDENTIFIER) . '</a>') . '</p></div>';
    }

    /**
    * WooCommerceSubscriptions fallback notice.
    * @return  string
    */
    public static function woocommerce_subscriptions_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Vindi Gateway depende da última versão do %s para funcionar!', VINDI_IDENTIFIER), '<a href="http://www.woothemes.com/products/woocommerce-subscriptions/">' . __('WooCommerce Subscriptions', VINDI_IDENTIFIER) . '</a>') . '</p></div>';
    }

    /**
    * WooCommerce Extra Checkout Fields for Brazil fallback notice.
    * @return  string
    */
    public static function extra_checkout_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Vindi Gateway depende da última versão do %s para funcionar!', VINDI_IDENTIFIER), '<a href="https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/">' . __('WooCommerce Extra Checkout Fields for Brazil', VINDI_IDENTIFIER) . '</a>') . '</p></div>';
    }

    /**
    * @return boolean
    **/
    public static function is_woocommerce_activated()
    {
        return in_array('woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists('woocommerce/woocommerce.php', self::$active_plugins);
    }

    /**
    * @return boolean
    **/
    public static function is_woocommerce_subscription_activated()
    {
        return in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', self::$active_plugins ) || array_key_exists('woocommerce-subscriptions/woocommerce-subscriptions.php', self::$active_plugins);
    }
}
