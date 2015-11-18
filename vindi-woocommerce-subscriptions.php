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

if (! defined('ABSPATH')) die('No script kiddies please!');

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
		const VIEWS_DIR = '/templates/';

        /**
         * @var string
         */
        const INCLUDES_DIR = '/includes/';

        /**
         * @var string
         */
        const WC_API_CALLBACK = 'vindi_webhook';

        /**
		 * Instance of this class.
		 * @var Vindi_WooCommerce_Subscriptions
		 */
		protected static $instance = null;

        /**
		 * Instance of Vindi_Settings.
		 * @var Vindi_Settings
		 */
		protected $settings = null;

        /**
		 * Instance of Vindi_Settings.
		 * @var Vindi_Webhook_Handler
		 */
		private $webhook_handler = null;

		public function __construct()
		{
			$this->includes();

			$this->settings = new Vindi_Settings();
            $this->webhook  = new Vindi_Webhook_Handler($this->settings);

            add_action('woocommerce_api_' . self::WC_API_CALLBACK, array(
                $this->webhook_handler, 'handle'
            ));

            if(is_admin()) {
                add_action('woocommerce_product_options_general_product_data',
                    array(&$this, 'vindi_subscription_pricing_fields')
                );
            }
		}

        /**
		 * Show pricing fields at admin's product page.
		 */
        public function vindi_subscription_pricing_fields()
        {
    		global $post;

    		echo '<div class="options_group vindi-subscription_pricing show_if_vindi-subscription">';

    		$plans         = [ __( '-- Selecione --', VINDI_IDENTIFIER ) ] + $this->settings->api->get_plans();
    		$selected_plan = get_post_meta( $post->ID, 'vindi_subscription_plan', true );

    		woocommerce_wp_select( [
    				'id'          => 'vindi_subscription_plan',
    				'label'       => __( 'Plano da Vindi', VINDI_IDENTIFIER ),
    				'options'     => $plans,
    				'description' => __( 'Selecione o plano da Vindi que deseja relacionar a esse produto', VINDI_IDENTIFIER ),
    				'desc_tip'    => true,
    				'value'       => $selected_plan,
    			]
    		);

    		woocommerce_wp_text_input( [
    				'id'                => 'vindi_subscription_price',
    				'label'             => sprintf( __( 'Preço da Assinatura (%s)', VINDI_IDENTIFIER ), get_woocommerce_currency_symbol() ),
    				'placeholder'       => __( '0,00', 'woocommerce-subscriptions' ),
    				'type'              => 'text',
    				'custom_attributes' => [
    					'step' => 'any',
    					'min'  => '0',
    				],
    				'description'       => __( 'Você deve manter o preço do produto igual ao do plano, este processo <strong>não</strong> é automático.', VINDI_IDENTIFIER ),
    				'desc_tip'          => true,
    			]
    		);

    		echo '</div>';
    		echo '<div class="show_if_vindi-subscription clear"></div>';
    	}
		/**
		 * Return an instance of this class.
		 * @return Vindi_WooCommerce_Subscriptions
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
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-logger.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-api.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-settings.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-base-gateway.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-bank-slip-gateway.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-creditcard-gateway.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-payment.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-webhook-handler.php');
			include_once(dirname(__FILE__) . self::INCLUDES_DIR . 'class-vindi-product-subscription.php');
		}

        /**
         * Generate assets URL
         * @param string $path
         **/
        public static function generate_assets_url($path)
        {
            return plugin_dir_url(__FILE__) . 'assets/' . $path;
        }
	}
}

add_action('wp_loaded', array('Vindi_WooCommerce_Subscriptions', 'get_instance'), 0);
