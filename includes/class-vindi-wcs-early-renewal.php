<?php
if (!defined('ABSPATH')) {
	die('not allowed');
}

class Vindi_WCS_Early_Renewal
{
	/**
     * Vindi Gateway.
     * @var Vindi_Settings
     */
    protected $container;

	/**
     * @param Vindi_Settings     $container
     */
	public function __construct(Vindi_Settings $container) {
		$this->container = $container;
		add_filter( 'wcs_view_subscription_actions', array( 
			&$this, 
			'replace_early_renewal_url'
		), 100, 2 );
		add_action('template_redirect', array(
			&$this,
			'early_renewal_action'
		), 100);
	}

	function replace_early_renewal_url($actions, $subscription){
			foreach ( $actions as $action_key => $action ) {
				if ($action_key == 'subscription_renewal_early'){

					if (!empty($subscription->get_meta('vindi_wc_subscription_id'))) {

						if (isset($_GET['vindi_subscription_renewal'])){
							unset($actions[$action_key]);
						} else {
							$subscription_id = is_a( $subscription, 'WC_Subscription' ) ? $subscription->get_id() : absint( $subscription );

							$url = add_query_arg( array(
								'vindi_subscription_renewal_early' => $subscription_id,
							), get_permalink( wc_get_page_id( 'myaccount' ) ) );

							$actions[$action_key]['url'] = $url;
						}
					}
					break;
				}
			}
			return $actions;
		}

	function early_renewal_action() {
		if ( ! isset( $_GET['vindi_subscription_renewal_early'] ) ) {
			return;
		}

		$wc_subscription = wcs_get_subscription( absint( $_GET['vindi_subscription_renewal_early'] ) );
		$redirect_to	 = $url = add_query_arg( array(
							'vindi_subscription_renewal' => 'true',
						), $wc_subscription->get_view_order_url() );

		if ( empty( $wc_subscription ) ) {

			wc_add_notice( __( 'That subscription does not exist. Has it been deleted?', 'woocommerce-subscriptions' ), 'error' );

		} elseif ( ! current_user_can( 'subscribe_again', $wc_subscription->get_id() ) ) {

			wc_add_notice( __( "That doesn't appear to be one of your subscriptions.", 'woocommerce-subscriptions' ), 'error' );

		} elseif ( ! wcs_can_user_renew_early( $wc_subscription ) ) {

			wc_add_notice( __( 'You can not renew this subscription early. Please contact us if you need assistance.', 'woocommerce-subscriptions' ), 'error' );

		} else {

			$subscription_id = $this->container->get_vindi_subscription_id($wc_subscription);

			if ($this->container->api->renew_subscription($subscription_id)){
				wc_add_notice( __('Renovação de assinatura antecipada! Em alguns minutos você receberá um email com mais informações.', VINDI_IDENTIFIER), 'success' );
			}

		}

		wp_safe_redirect( $redirect_to );
		exit;
	}
}
