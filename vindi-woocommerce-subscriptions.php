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
		const VERSION = '1.0.0';

		/**
		 * @var string
		 */
		const VIEWS_DIR = __DIR__.'/views';

		/**
		 * Instance of this class.
		 * @var WCS_Vindi
		 */
		protected static $instance = null;

		/**
		 * Set up the class, including it's hooks & filters, when the file is loaded.
		 **/
		public function __construct()
		{
			$this->includes();

			$this->settings = new Vindi_Settings($this);
			$this->settings->init();
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
		 * Include the dependents classes
		 **/
		public function includes()
		{
			include_once(__DIR__.'/src/class-vindi-settings.php');
		}

		/**
		 * Check if SSL is enabled when merchant is not trial.
		 * @return boolean
		 */
		public function checkSsl()
		{
			return false;
		}
	}
}

add_action( 'admin_init', [ 'Vindi_WooCommerce_Subscriptions', 'get_instance' ], 100 );
