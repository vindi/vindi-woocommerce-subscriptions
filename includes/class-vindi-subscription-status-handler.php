<?php

class Vindi_Subscription_Status_Handler{
	/**
	 * @var Vindi_Settings
	 **/
	private $container;

	public function __construct( Vindi_Settings $container ) {
		$this->container = $container;

		add_action( 'woocommerce_subscription_status_updated', array(
			&$this,
			'filter_pre_status'
		), 1, 3 );
	}

	/**
	 * @param WC_Subscription $wc_subscription
	 * @param string          $new_status
	 * @param string          $old_status
	 **/
	public function filter_pre_status( $wc_subscription, $new_status, $old_status ) {

		$subscription_id = $this->get_vindi_subscription_id( $wc_subscription );


		$result = apply_filters( 'vindi_status_handler_filter_pre_status', null, $wc_subscription, $new_status, $old_status, $this );

		if ( null !== $result ) {
			return;
		}

		if ( ! $this->container->get_synchronism_status() ) {
			return;
		}


		switch ( $new_status ) {
			case 'on-hold':
				$this->suspend_status( $subscription_id );
				break;
			case 'active':
				$this->active_status( $subscription_id );
				break;
			case 'pending-cancel':
				if ( ! $this->container->dependency->wc_memberships_are_activated() ) {
					$wc_subscription->update_status( 'cancelled' );
				}
				break;
			default:
				$this->suspend_status( $subscription_id );
				break;
		}

	}

	public function suspend_status( $subscription_id ) {
		$this->container->api->suspend_subscription( $subscription_id );
	}

	/**
	 * @param string $subscription_id
	 **/
	public function cancelled_status( $subscription_id ) {
		$this->container->api->suspend_subscription( $subscription_id, true );
	}

	/**
	 * @param string $subscription_id
	 **/
	public function active_status( $subscription_id ) {
		$this->container->api->activate_subscription( $subscription_id );
	}

	/**
	 * @param WC_Subscription $wc_subscription
	 *
	 * @return string vindi_subscription_ic
	 **/
	public function get_vindi_subscription_id( $wc_subscription ) {
		$subscription_id = method_exists( $wc_subscription, 'get_id' )
			? $wc_subscription->get_id()
			: $wc_subscription->id;

		return end( get_post_meta( $subscription_id, 'vindi_wc_subscription_id' ) );
	}
}
