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

define('VINDI_IDENTIFIER', 'vindi-woocommerce-subscriptions');

require_once __DIR__."/src/class-vindi-dependencies.php";

if ( ! Vindi_Dependencies::check('woocommerce')) {
	add_action( 'admin_notices', 'Vindi_Dependencies::woocommerceMissingNotice' );
	return;
}

if ( ! class_exists( 'Vindi_WooCommerce_Subscriptions' ) )
{
    class Vindi_WooCommerce_Subscriptions
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
         * Set up the class, including it's hooks & filters, when the file is loaded.
         **/
        public static function init()
        {
            $GLOBALS[VINDI_IDENTIFIER] = self::get_instance();
        }

        /**
         * Initialize the plugin public actions.
         */
        private function __construct()
        {

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
    }
}

Vindi_WooCommerce_Subscriptions::init();
