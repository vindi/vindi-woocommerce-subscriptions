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

require_once dirname(__FILE__)."/includes/class-vindi-dependencies.php";

/**
* Check all Vindi Dependencies
*/
if(false === Vindi_Dependencies::check()) {
	return ;
}

if (! class_exists('Vindi_WooCommerce_Subscriptions'))
{
	class Vindi_WooCommerce_Subscriptions
	{
	    /**
		 * @var string
		 */
		const VERSION = '1.0.0';

        /**
		 * @var string
		 */
		const VIEWS_DIR = __DIR__.'/templates';

        /**
         * @var string
         */
        const INCLUDES_DIR = __DIR__.'/includes';

        /**
		 * Instance of this class.
		 * @var WCS_Vindi
		 */
		protected static $instance = null;

        /**
		 * Instance of Vindi_Settings.
		 * @var Vindi_Settings
		 */
		protected $settings = null;

		/**
		 * Set up the class, including it's hooks & filters, when the file is loaded.
		 **/
		public function __construct()
		{
			$this->includes();

			$this->settings = new Vindi_Settings();
		}

		/**
		 * Return an instance of this class.
		 * @return WCS_Vindi A single instance of this class.
		 */
		public static function get_instance()
		{
			// If the single instance hasn't been set, set it now.
			if (null === self::$instance)
                self::$instance = new self;

			return self::$instance;
		}

		/**
		 * Include the dependents classes
		 **/
		public function includes()
		{
			include_once(self::INCLUDES_DIR . '/class-vindi-logger.php');
			include_once(self::INCLUDES_DIR . '/class-vindi-api.php');
			include_once(self::INCLUDES_DIR . '/class-vindi-settings.php');
			include_once(self::INCLUDES_DIR . '/class-vindi-base-gateway.php');
			include_once(self::INCLUDES_DIR . '/class-vindi-bank-slip-gateway.php');
		}
	}
}

add_action( 'admin_init', [ 'Vindi_WooCommerce_Subscriptions', 'get_instance' ], 100 );
