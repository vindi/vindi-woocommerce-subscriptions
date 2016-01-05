<?php
/**
* Plugin Name: Vindi Woocommerce Subscriptions
* Plugin URI:
* Description: Adiciona o gateway de pagamentos da Vindi para o WooCommerce Subscriptions.
* Version: 0.2.2
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
		const VERSION = '0.2.2';

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
		 * @var Vindi_WooCommerce_Subscriptions
		 */
		protected static $instance = null;

        /**
		 * @var Vindi_Settings
		 */
		protected $settings = null;

        /**
		 * @var Vindi_Webhook_Handler
		 */
		private $webhook_handler = null;

        /**
		 * @var Vindi_Subscription_Status_Handler
		 */
		private $subscription_status_handler = null;

		public function __construct()
		{
			$this->includes(array(
                'class-vindi-logger.php',
    			'class-vindi-api.php',
    			'class-vindi-settings.php',
    			'class-vindi-base-gateway.php',
    			'class-vindi-bank-slip-gateway.php',
    			'class-vindi-creditcard-gateway.php',
    			'class-vindi-payment.php',
    			'class-vindi-webhook-handler.php',
    			'class-vindi-subscription-status-handler.php',
            ));

			$this->settings                    = new Vindi_Settings();
            $this->webhook_handler             = new Vindi_Webhook_Handler($this->settings);
            $this->subscription_status_handler = new Vindi_Subscription_Status_Handler($this->settings);

            add_action('woocommerce_api_' . self::WC_API_CALLBACK, array(
                $this->webhook_handler, 'handle'
            ));

            add_action('woocommerce_add_to_cart_validation', array(
                &$this, 'validate_add_to_cart'
            ), 1, 3);

            // add_action('woocommerce_update_cart_validation', array(
            //     &$this, 'validate_update_cart'
            // ), 1, 4);

            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
                &$this, 'action_links'
            ));

            add_filter('wcs_view_subscription_actions', array(
                &$this, 'user_subscriptions_actions'
            ), 100, 2);

            add_filter('woocommerce_my_account_my_orders_actions', array(
                &$this, 'user_related_orders_actions'
            ), 100, 2);

            if(is_admin()) {

                add_action('admin_enqueue_scripts', array(
                    &$this, 'add_admin_scripts'
                ));

                add_action('woocommerce_product_options_general_product_data',
                    array(&$this, 'subscription_custom_fields')
                );

                add_action('save_post',
                    array(&$this, 'save_subscription_meta')
                );

            }
		}

        /**
		 * Show pricing fields at admin's product page.
		 */
        public function subscription_custom_fields()
        {
            global $post;

            $plans         = $this->settings->api->get_plans();
    		$selected_plan = get_post_meta($post->ID, 'vindi_subscription_plan', true);

            $plans['names'] = array(__('-- Selecione --', VINDI_IDENTIFIER)) + $plans['names'];

            $this->settings->get_template(
                'admin-product-subscription-fields.html.php',
                compact(
                    'plans',
                    'selected_plan'
                )
            );
    	}

        /**
         * @param int $post_id
         */
        public function save_subscription_meta($post_id)
        {
            if (! isset($_POST['product-type']) || ('subscription' !== $_POST['product-type']))
                return;

            $subscription_plan = filter_input(INPUT_POST, 'vindi_subscription_plan', FILTER_SANITIZE_NUMBER_INT);
            update_post_meta($post_id, 'vindi_subscription_plan', $subscription_plan);
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
         * @param array $classes
		 **/
		public function includes(array $classes)
		{
            foreach ($classes as $class)
                include_once(dirname(__FILE__) . self::INCLUDES_DIR . $class);
		}

        /**
         * Generate assets URL
         * @param string $path
         **/
        public static function generate_assets_url($path)
        {
            return plugin_dir_url(__FILE__) . 'assets/' . $path;
        }

        /**
         * Include Settings link on the plugins administration screen
         * @param mixed $links
         */
        public function action_links($links)
        {
            $links[] = '<a href="admin.php?page=wc-settings&tab=settings_vindi">' . __('Configurações', VINDI_IDENTIFIER) . '</a>';
            return $links;
        }

        /**
		 * @param bool $valid
		 * @param int  $product_id
		 * @param int  $quantity
		 *
		 * @return bool
		 */
		public function validate_add_to_cart($valid, $product_id, $quantity)
        {
            $cart       = $this->settings->woocommerce->cart;
			$cart_items = $cart->get_cart();

			$product = wc_get_product($product_id);

			if (empty($cart_items))
				return $valid;

            if ($product->is_type('subscription')) {

                $product_vindi_subscription_plan_meta = get_post_meta($product->post->ID, 'vindi_subscription_plan');
                $product_vindi_subscription_plan_id   = (int) end($product_vindi_subscription_plan_meta);

                foreach($cart_items as $item)
                {
                    if ('subscription' === $item['data']->product_type) {

                        $item_vindi_subscription_plan_meta = get_post_meta($item['data']->post->ID, 'vindi_subscription_plan');
                        $item_vindi_subscription_plan_id   = (int) end($item_vindi_subscription_plan_meta);

                        if($product_vindi_subscription_plan_id != $item_vindi_subscription_plan_id) {
                            wc_add_notice(__('Você só pode adicionar produtos que façam parte do mesmo plano!', VINDI_IDENTIFIER), 'error');
                            return false;
                        }
                    }
                }
            }

			return $valid;
		}

		// /**
		//  * @param bool $valid
		//  * @param      $cart_item_key
		//  * @param      $values
		//  * @param int  $quantity
		//  *
		//  * @return bool
		//  */
		// public function validate_update_cart($valid, $cart_item_key, $values, $quantity)
        // {
        //     // $cart    = $this->settings->woocommerce->cart;
        //     // $item    = $cart->get_cart_item($cart_item_key);
		// 	// $product = $item['data'];
        //     //
		// 	// if ($product->is_type('subscription') && 1 !== $quantity && 0 !== $quantity) {
		// 	// 	wc_add_notice(__('Você pode fazer apenas uma assinatura a cada vez.', VINDI_IDENTIFIER), 'error');
        //     //
		// 	// 	return false;
		// 	// }
        //
		// 	return $valid;
		// }

        /**
         * @param array           $actions
         * @param WC_Subscription $subscription
         **/
        public function user_subscriptions_actions($actions, $subscription)
        {
            // remove from second array to allow action
            $filtred_actions = $this->filter_actions($actions, array(
                'resubscribe',
                'suspend',
                'reactivate',
                //'cancel',
            ));

            return $filtred_actions;
        }

        /**
         * @param array    $actions
         * @param WC_Order $order
         **/
        public function user_related_orders_actions($actions, $order)
        {
            //remove from second array to allow action
            $filtred_actions = $this->filter_actions($actions, array(
                'pay',
                'cancel',
                //'view',
            ));

            return $filtred_actions;
        }

        /**
         * @param array $actions
         * @param array $filter
         */
        private function filter_actions($actions, $filter)
        {
            $filtred_actions      = array();
            $filtred_actions_keys = array_diff(array_keys($actions), $filter);

            foreach ($filtred_actions_keys as $key)
                $filtred_actions[$key] = $actions[$key];

            return $filtred_actions;
        }

        /**
         */
        public function add_admin_scripts()
        {
            return $this->settings->add_script('js/simple-subscription-fields.js', array('jquery'));
        }
	}
}

add_action('wp_loaded', array('Vindi_WooCommerce_Subscriptions', 'get_instance'), 0);
