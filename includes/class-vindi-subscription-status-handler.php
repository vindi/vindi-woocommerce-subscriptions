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
    }

    /**
     * @param WC_Subscription $wc_subscription
     **/
    public function cancelled_subscription($wc_subscription)
    {
        $vindi_subscription_id_meta = get_post_meta($wc_subscription->id, 'vindi_wc_subscription_id');
        $vindi_subscription_id      = end($vindi_subscription_id_meta);

        $this->container->api->delete_subscription($vindi_subscription_id, true);
    }
}
