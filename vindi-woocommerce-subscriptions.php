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
		public function __construct()
		{
			add_filter('woocommerce_settings_tabs_array', [&$this, 'add_settings_tab'], 100);
			add_action('woocommerce_settings_tabs_'.VINDI_IDENTIFIER, [&$this, 'settings_tab']);
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
			$settings_tabs[VINDI_IDENTIFIER] = __('Vindi', 'vindi-settings');

			return $settings_tabs;
		}

		/**
		 * Check if SSL is enabled when merchant is not trial.
		 * @return boolean
		 */
		protected function checkSsl()
		{
			return false;
		}

		public function settings_tab()
		{
		    require_once(self::VIEWS_DIR.'/admin-settings.html.php');
		}

		function generate_settings_html()
		{
		    $settings = array(
		        'section_title' => array(
		            'name'     => __( 'Section Title', 'woocommerce-settings-tab-demo' ),
		            'type'     => 'title',
		            'desc'     => '',
		            'id'       => 'wc_settings_tab_demo_section_title'
		        ),
		        'title' => array(
		            'name' => __( 'Title', 'woocommerce-settings-tab-demo' ),
		            'type' => 'text',
		            'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
		            'id'   => 'wc_settings_tab_demo_title'
		        ),
		        'description' => array(
		            'name' => __( 'Description', 'woocommerce-settings-tab-demo' ),
		            'type' => 'textarea',
		            'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
		            'id'   => 'wc_settings_tab_demo_description'
		        ),
		        'section_end' => array(
		             'type' => 'sectionend',
		             'id' => 'wc_settings_tab_demo_section_end'
		        )
		    );
		    return woocommerce_admin_fields(apply_filters( 'wc_settings_tab_vindi_settings', $settings ));
		}
	}
}

add_action( 'admin_init', [ 'Vindi_WooCommerce_Subscriptions', 'get_instance' ], 100 );
