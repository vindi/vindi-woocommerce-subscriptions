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
        ), 1, 2);
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function filter_pre_status($wc_subscription, $new_status)
    {

        $subscripton_id = $this->get_vindi_subscription_id($wc_subscription);

        switch ($new_status) {
            case 'on-hold':
                $this->suspend_status($subscripton_id);
                break;
            case 'active':
                $this->active_status($wc_subscription);
                break;           
            default:
                $this->cancelled_status($wc_subscription,$new_status);
                break;
        }
    }

    /**
     * @param WC_Subscription $subscripton_id
     **/
    public function suspend_status($subscripton_id)
    {
        $this->container->api->suspend_subscription($subscripton_id);
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function cancelled_status($wc_subscription,$new_status)
    {
        $subscripton_id = $this->get_vindi_subscription_id($wc_subscription);
        $wc_memberships = Vindi_Dependencies::wc_memberships_are_activated();

        if(false == $wc_memberships && 'pending-cancel' === $new_status) {
            $wc_subscription->update_status('cancelled');
        }

        $this->container->api->suspend_subscription($subscripton_id, true); 
    }

    /**
     * @param WC_Subscription $wc_subscription
     **/
    public function active_status($wc_subscription)
    {
        if($wc_subscription->has_status('on-hold')){
            $this->container->api->activate_subscription($subscripton_id);
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