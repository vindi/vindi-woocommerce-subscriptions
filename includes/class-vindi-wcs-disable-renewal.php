<?php
if (!defined('ABSPATH')) {
	die('not allowed');
}

new Vindi_WCS_Disable_Renewal;

class Vindi_WCS_Disable_Renewal
{
	public function __construct() {
		//hook as early as possible to try disabling WC_Subcriptions_Manager handling
		add_action( 'wp_loaded', array( __CLASS__, 'hook_before_prepare_renewal' ), 1 );
	}

	static function hook_before_prepare_renewal() {
		//check if subscription manager exists
		if (class_exists( 'WC_Subscriptions_Manager', false )) {
			//prepare_renewal runs on 1 priority, so we hook in first
			add_action('woocommerce_scheduled_subscription_payment', array(
				__CLASS__,
				'maybe_deactivate_prepare_renewal'
			), 0, 1);
		}
	}

	static function maybe_deactivate_prepare_renewal($subscription_id) {

		$subscription = wcs_get_subscription($subscription_id);

		//easy check to see if this subscriptions is a Vindi Subscription
		if (!empty($subscription->get_meta('vindi_wc_subscription_id'))) {
			//if it is we disable Woocommerce Subscriptions Renewal order and let Vindi handle it via webhooks
			remove_action('woocommerce_scheduled_subscription_payment',
				'WC_Subscriptions_Manager::prepare_renewal', 1);
		}
	}
}
