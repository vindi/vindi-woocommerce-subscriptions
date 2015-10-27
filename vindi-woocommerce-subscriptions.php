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

define('VINDI_IDENTIFIER', 'vindi_subscriptions');

require_once dirname(__FILE__)."/src/class-vindi-dependencies.php";

/**
 * Check all Vindi Dependencies
 */
if( false === Vindi_Dependencies::check()) {
	return ;
}

if ( ! class_exists( 'Vindi_WooCommerce_Subscriptions' ) )
{
    class Vindi_WooCommerce_Subscriptions
    {
        /**
         * @var string
         */
        CONST VERSION = '1.0.0';

				/**
         * @var string
         */
        CONST VIEWS_DIR = 'views';

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

						add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 100 );
						add_action( 'woocommerce_settings_tabs_'.VINDI_IDENTIFIER, __CLASS__ . '::load_settings_tab' );
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
				 * Create a tab settings at woocommerce tabs
				 */
				public function add_settings_tab( $settings_tabs )
				{
					$settings_tabs[VINDI_IDENTIFIER] = __( 'Vindi', 'vindi-settings' );

	        return $settings_tabs;
				}

				/**
				 * Create settings fields in Vind settings tab
				 **/
				public function load_settings_tab()
				{
					include_once(self::VIEWS_DIR.'/admin-settings.html.php');
				}
    }
}

Vindi_WooCommerce_Subscriptions::init();
