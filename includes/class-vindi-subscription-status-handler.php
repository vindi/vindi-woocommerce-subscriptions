<?php

class Vindi_Subscription_Status_Handler
{
    /**
     * @var Vindi_Settings
     **/
    private $container;

    public function __construct(Vindi_Settings $container)
    {
        $this->container = $container;

        add_action('woocommerce_subscription_status_cancelled',array(
            &$this, 'cancelled_status'
        ));

        add_action('woocommerce_subscription_status_updated',array(
            &$this, 'filter_pre_status'
        ), 1, 3);
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function filter_pre_status($wc_subscription, $new_status, $old_status)
    {
	    $vindi_subscription_id = $this->get_vindi_subscription_id( $wc_subscription );

        switch ($new_status) {
            case 'on-hold':
                $this->suspend_status($this->get_vindi_subscription_id($wc_subscription));
                break;
            case 'active':
            	if( 'on-hold' == $old_status ) {
		            $this->active_status( $vindi_subscription_id );
	            }
                break;           
            default:
                $this->cancelled_status($wc_subscription,$new_status);
                break;
        }
    }

    public function suspend_status($vindi_subscription_id)
    {
        if ($this->container->get_synchronism_status()) {
            $this->container->api->suspend_subscription($vindi_subscription_id);
        }
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function cancelled_status($wc_subscription,$new_status = 'cancelled')
    {
        if (!$this->container->dependency->wc_memberships_are_activated() && 'pending-cancel' === $new_status) {
            return $wc_subscription->update_status('cancelled');
        }
        if ($this->container->api->is_subscription_canceled($this->vindi_subscription_id)) {
            $this->container->api->suspend_subscription($this->vindi_subscription_id, true); 
        }
    }

    /**
     * @param string $vindi_subscription_id
     **/
    public function active_status( $vindi_subscription_id )
    {
        if ( $this->container->get_synchronism_status() ) {
            $this->container->api->activate_subscription( $vindi_subscription_id );
        }
    }

    /**
     * @param WC_Subscription $wc_subscription
     **/
    public function get_vindi_subscription_id($wc_subscription)
    {
        return end(get_post_meta($wc_subscription->id, 'vindi_wc_subscription_id'));
    }
}
