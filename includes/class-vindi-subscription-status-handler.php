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
        switch ($new_status) {
            case 'on-hold':
                $this->suspend_status($this->get_vindi_subscription_id($wc_subscription));
                break;
            case 'active':
                $this->active_status($wc_subscription);
                break;           
            default:
                $this->cancelled_status($wc_subscription,$new_status);
                break;
        }
    }

    public function suspend_status($vindi_subscription_id)
    {
        if($this->container->get_synchronism_status()){
            $this->container->api->suspend_subscription($vindi_subscription_id);
        }
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function cancelled_status($wc_subscription,$new_status)
    {
        if(false == Vindi_Dependencies::wc_memberships_are_activated() && 'pending-cancel' === $new_status) {
            $wc_subscription->update_status('cancelled');
        }

        $this->container->api->suspend_subscription($this->get_vindi_subscription_id($wc_subscription), true); 
    }

    /**
     * @param WC_Subscription $wc_subscription
     **/
    public function active_status($wc_subscription)
    {
        if($wc_subscription->has_status('on-hold') && $this->container->get_synchronism_status()){
            $this->container->api->activate_subscription($this->vindi_subscription_id);
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