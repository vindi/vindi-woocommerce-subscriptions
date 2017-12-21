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
            &$this, 'cancelled_subscription'
        ));

        add_action('woocommerce_subscription_status_updated',array(
            &$this, 'filter_pre_cancelled_status'
        ), 1, 2);
    }

    /**
     * @param WC_Subscription $wc_subscription
     **/
    public function cancelled_subscription($wc_subscription)
    {
        $this->container->api->delete_subscription($this->get_vindi_subscription_id($wc_subscription), true);
    }

    /**
     * @param WC_Subscription $wc_subscription
     * @param string          $new_status
     **/
    public function filter_pre_cancelled_status($wc_subscription, $new_status)
    {
        if('pending-cancel' === $new_status) {
            $wc_memberships = Vindi_Dependencies::wc_memberships_are_activated();
            if(false == $wc_memberships) {
                $wc_subscription->update_status('cancelled');
            } else {
                $this->container->api->delete_subscription($this->get_vindi_subscription_id($wc_subscription), true);
            }
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
