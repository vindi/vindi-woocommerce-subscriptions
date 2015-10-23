<?php
/**
 * Plugin Name: Vindi Woocommerce Subscriptions
 * Plugin URI:
 * Description: Adiciona o gateway de pagamentos da Vindi para o WooCommerce Subscriptions.
 * Version: 1.0.0
 * Author: Vindi
 * Author URI: https://www.vindi.com.br
 * Requires at least: 4.0
 * Tested up to: 4.2
 *
 * Text Domain: vindi-woocommerce-subscriptions
 * Domain Path: /languages/
 *
 * Copyright: © 2014-2015 Vindi Tecnologia e Marketing LTDA
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) die( 'No script kiddies please!' );

if ( ! class_exists( 'WCS_Vindi' ) )
{
    class WCS_Vindi
    {

        /**
         * @var string
         */
        public $version = '1.0.0';

        /**
         * Instance of this class.
         * @var WCS_Vindi
         */
        protected static $instance = null;

        /**
         * Initialize the plugin public actions.
         */
        private function __construct()
        {
            // Checks if WooCommerce is installed.
            if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
                add_action( 'admin_notices', [ &$this, 'woocommerceMissingNotice' ] );

                return;
            }

            // Checks if WooCommerce Extra Checkout Fields for Brazil is installed.
            if ( ! class_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
                add_action( 'admin_notices', [ &$this, 'extraCheckoutMissingNotice' ] );

                return;
            }

            define( 'WCS_VINDI_VERSION', $this->version );
        }

        /**
         * Return an instance of this class.
         * @return WCS_Vindi A single instance of this class.
         */
        public static function get_instance()
        {
            // If the single instance hasn't been set, set it now.
            if ( null === self::$instance )
                 self::$instance = new self;

            return self::$instance;
        }

        /**
         * Included files.
         * @return void
         */
        private function includes()
        {

        }
    }
}
