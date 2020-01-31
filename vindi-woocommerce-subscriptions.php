<?php
/**
 * Plugin Name: Vindi Woocommerce
 * Plugin URI:
 * Description: Adiciona o gateway de pagamentos da Vindi para o WooCommerce.
 * Version: 5.5.4
 * Author: Vindi
 * Author URI: https://www.vindi.com.br
 * Requires at least: 4.4
 * Tested up to: 5.3.2
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.1
 *
 * Text Domain: vindi-woocommerce-subscriptions
 * Domain Path: /languages/
 *
 * Copyright: © 2014-2020 Vindi Tecnologia e Marketing LTDA
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
        const VERSION = '5.5.4';

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
                'class-vindi-wcs-disable-renewal.php',
            ));

			$this->settings                    = new Vindi_Settings();
            $this->webhook_handler             = new Vindi_Webhook_Handler($this->settings);
            $this->subscription_status_handler = new Vindi_Subscription_Status_Handler($this->settings);

            add_action('http_api_curl', [ &$this, 'add_support_to_tlsv1_2' ]);

            add_action('woocommerce_api_' . self::WC_API_CALLBACK, array(
                $this->webhook_handler, 'handle'
            ));

            add_action('woocommerce_add_to_cart_validation', array(
                &$this, 'validate_add_to_cart'
            ), 1, 3);

            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
                &$this, 'action_links'
            ));

            add_filter('woocommerce_my_account_my_orders_actions', array(
                &$this, 'user_related_orders_actions'
            ), 100, 2);

            add_filter('woocommerce_subscription_period_interval_strings', array(
                &$this, 'set_supported_intervals')
            );

            add_action('woocommerce_customer_save_address', array(
                &$this, 'sync_vindi_user_information'
            ), 1, 2 );

            if (is_admin()) {
                add_action('admin_enqueue_scripts', array(
                    &$this, 'add_admin_scripts'
                ));

                $this->set_vindi_subscription_fields();

                add_action('woocommerce_process_product_meta',
                    array(&$this, 'save_subscription_meta')
                , 20);

                add_action('save_post',
                    array(&$this, 'save_subscription_meta')
                , 20);

                add_action('woocommerce_save_product_variation',
                    array(&$this, 'save_subscription_variation_meta')
                , 20, 2);
            } else {
                add_filter( 'woocommerce_order_item_get_formatted_meta_data',
                    array( &$this, 'hide_item_meta_data'), 10, 1 );
            }
		}

        /**
         * Enable/Disable Vindi subscription fields according to WCS
         */
        public function set_vindi_subscription_fields()
        {
            if ($this->settings->dependency->wc_subscriptions_are_activated()) {
                add_action('woocommerce_product_options_general_product_data',
                    array(&$this, 'simple_subscription_custom_fields'));
                add_action('woocommerce_product_after_variable_attributes',
                    array(&$this, 'variable_subscription_custom_fields'), 10, 3); 
            } else {
                remove_action('woocommerce_product_options_general_product_data',
                    array(&$this, 'simple_subscription_custom_fields'));
                remove_action('woocommerce_product_after_variable_attributes',
                    array(&$this, 'variable_subscription_custom_fields'), 10, 3);  
            }
        } 

        /**
         * Set supported intervals number for subscription plans
         */
        public function set_supported_intervals($current_intervals)
        {
            foreach (range(7, 60) as $new_interval) {
                array_push($current_intervals, $new_interval);
            }
            return $current_intervals;
        }

        /**
         * Update user informations from My Account form
         */
        public function sync_vindi_user_information($user_id, $address_type)
        {
            if (wc_notice_count( 'error' ) > 0 
                || empty( $_POST['_wcsnonce'] ) 
                || ! wp_verify_nonce( $_POST['_wcsnonce'], 'wcs_edit_address' ) 
                || 'billing' !== $address_type) {
                return;
            }

            $user_code      = get_user_meta($user_id, 'vindi_user_code', true);
            $address_fields = WC()->countries->get_address_fields( esc_attr( $_POST[ $address_type . '_country' ] ), $address_type . '_' );
            $address        = array();

            foreach ( $address_fields as $key => $field ) {
                if ( isset( $_POST[ $key ] ) ) {
                    $address[ str_replace( $address_type . '_', '', $key ) ] = wc_clean( $_POST[ $key ] );
                }
            }

            $this->settings->api->update_user_billing_informations($user_code, $address);
        }

        /**
         * Show pricing fields at admin's product page.
         */
        public function simple_subscription_custom_fields()
        {
            global $post;

            $plans          = $this->settings->api->get_plans();
            $selected_plan  = get_post_meta($post->ID, 'vindi_subscription_plan', true);
            $wc_product     = wc_get_product($post->ID);
            $product_type   = $wc_product->get_type();
            $plans['names'] = array(__('-- Selecione --', VINDI_IDENTIFIER)) + $plans['names'];

            $this->settings->get_template(
                'admin-simple-product-subscription-fields.html.php',
                compact(
                    'plans',
                    'selected_plan',
                    'product_type'
                )
            );
        }

        /**
         * Show pricing fields at admin's variable product page.
         */
        public function variable_subscription_custom_fields($loop, $variation_data, $variation)
        {
            global $post;

            $plans         = $this->settings->api->get_plans();
            $selected_plan = get_post_meta($variation->ID, 'vindi_variable_subscription_plan', true);

            $plans['names'] = array(__('-- Plano padrão da Vindi --', VINDI_IDENTIFIER)) + $plans['names'];

            $this->settings->get_template(
                'admin-variable-product-subscription-fields.html.php',
                compact(
                    'plans',
                    'selected_plan',
                    'loop'
                )
            );
        }

        /**
         * @param int $post_id
         */
        public function save_subscription_meta($post_id)
        {
            if (false === $this->is_product_type_from_post(['subscription','variable-subscription'])) {
                return;
            }

            $wc_product                   = wc_get_product($post_id);
            $subscription_plan            = wc_clean($_POST['vindi_subscription_plan']);
            $subscription_period_interval = wc_clean($_POST['_subscription_period_interval']);
            $subscription_period          = wc_clean($_POST['_subscription_period']);
            $subscription_length          = wc_clean($_POST['_subscription_length']);

            if (empty($subscription_period_interval)) {
                return;
            }

            if ($this->is_yearly_plan($subscription_period_interval, $subscription_period)) {
                $years_interval = (int) $subscription_period_interval / 12;
                update_post_meta($post_id, '_subscription_period_interval', $years_interval);
                update_post_meta($post_id, '_subscription_period', 'year');
                update_post_meta($post_id, 'vindi_subscription_period_interval', $years_interval);
                update_post_meta($post_id, 'vindi_subscription_period', 'year');
            } else if ($this->is_weekly_plan($subscription_period_interval, $subscription_period)) {
                $weeks_interval = (int) $subscription_period_interval / 7;
                update_post_meta($post_id, '_subscription_period_interval', $weeks_interval);
                update_post_meta($post_id, '_subscription_period', 'week');
                update_post_meta($post_id, 'vindi_subscription_period_interval', $weeks_interval);
                update_post_meta($post_id, 'vindi_subscription_period', 'week');
            } else {
                update_post_meta($post_id, '_subscription_period_interval', $subscription_period_interval);
                update_post_meta($post_id, '_subscription_period', $subscription_period);
                update_post_meta($post_id, 'vindi_subscription_period_interval', $subscription_period_interval);
                update_post_meta($post_id, 'vindi_subscription_period', $subscription_period);
            }

            update_post_meta($post_id, 'vindi_subscription_plan', $subscription_plan);
        }

        /**
         * @param int $variation_id
         */
        public function save_subscription_variation_meta($variation_id, $i)
        {
            $child_subscription_plan            = wc_clean($_POST['vindi_variable_subscription_plan'][$i]);
            $child_subscription_period_interval = wc_clean($_POST['variable_subscription_period_interval'][$i]);
            $child_subscription_period          = wc_clean($_POST['variable_subscription_period'][$i]);
            $child_subscription_length          = wc_clean($_POST['variable_subscription_length'][$i]);

            update_post_meta($variation_id, '_subscription_length', $child_subscription_length);
            if ($this->is_yearly_plan($child_subscription_period_interval, $child_subscription_period)) {
                $child_years_interval = (int) $child_subscription_period_interval / 12;
                update_post_meta($variation_id, '_subscription_period_interval', $child_years_interval);
                update_post_meta($variation_id, '_subscription_period', 'year');
                update_post_meta($variation_id, 'vindi_subscription_period_interval', $child_years_interval);
                update_post_meta($variation_id, 'vindi_subscription_period', 'year');
            } else if ($this->is_weekly_plan($child_subscription_period_interval, $child_subscription_period)) {
                $child_weeks_interval = (int) $child_subscription_period_interval / 7;
                update_post_meta($variation_id, '_subscription_period_interval', $child_weeks_interval);
                update_post_meta($variation_id, '_subscription_period', 'week');
                update_post_meta($variation_id, 'vindi_subscription_period_interval', $child_weeks_interval);
                update_post_meta($variation_id, 'vindi_subscription_period', 'week');
            } else {
                update_post_meta($variation_id, '_subscription_period_interval', $child_subscription_period_interval);
                update_post_meta($variation_id, '_subscription_period', $child_subscription_period);
                update_post_meta($variation_id, 'vindi_subscription_period_interval', $child_subscription_period_interval);
                update_post_meta($variation_id, 'vindi_subscription_period', $child_subscription_period);
            }

            update_post_meta($variation_id, 'vindi_variable_subscription_plan', $child_subscription_plan);
        }

        private function is_product_type_from_post($allow_types)
        {
            return in_array(
                wc_clean($_POST['product-type']),
                $allow_types
            ) && 'product' !== wc_clean($_POST['post-type']);
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
         * Returns if it is an weekly periodicity
         * @return bool
         */
        public function is_weekly_plan($period_interval, $subscription_period)
        {
            return $period_interval % 7 == 0
                && $period_interval <= 28
                && $subscription_period == 'day';
        }

        /**
         * Returns if it is an annual periodicity
         * @return bool
         */
        public function is_yearly_plan($period_interval, $subscription_period)
        {
            return $period_interval % 12 == 0 && $subscription_period == 'month';
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

        /**
         * @param resource $ch Curl Resource
         **/
        public function add_support_to_tlsv1_2($ch)
        {
            if(empty($ch))
                return;

            $host_to = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL), PHP_URL_HOST);

            if($host_to !== 'app.vindi.com.br')
                return;

            if(!defined('CURL_SSLVERSION_TLSv1_2'))
                return;

            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        }

        /**
         * @param array $formatted_meta
         **/
        function hide_item_meta_data($formatted_meta){
            $temp_metas = [];
            foreach($formatted_meta as $key => $meta) {
                if ( isset( $meta->key ) && ! in_array( $meta->key, [
                        'type',
                        'vindi_id',
                        'price'
                    ] ) ) {
                    $temp_metas[ $key ] = $meta;
                }
            }
            return $temp_metas;
        }
	}
}

add_action('wp_loaded', array('Vindi_WooCommerce_Subscriptions', 'get_instance'), 0);
