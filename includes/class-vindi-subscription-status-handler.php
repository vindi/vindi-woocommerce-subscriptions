<?php

class Vindi_Subscription_Status_Handler
{
    /**
     * @var Vindi_Settings
     **/
    private $container;

    /**
     * @var Wc_Subscription
     **/
    private $wc_subscription;

    /**
     * @var int
     **/
    private $vindi_subscription_id;

    public function __construct(Vindi_Settings $container)
    {
        $this->container = $container;

        add_action('woocommerce_subscription_status_updated',array(
            &$this, 'handle'
        ), 1, 3);
    }

    /**
     * @param Wc_Subscription $wc_subscription
     * @param string          $new_status
     * @param string          $old_status
     **/
    public function handle($wc_subscription, $new_status, $old_status)
    {
        $this->wc_subscription       = $wc_subscription;
        $vindi_subscription_id_meta  = get_post_meta($this->wc_subscription->id, 'vindi_wc_subscription_id');
        $this->vindi_subscription_id = end($vindi_subscription_id_meta);

        $this->update_vindi_subscription($new_status, $old_status);
    }

    /**
     * @param string $new_status
     * @param string $old_status
     **/
    private function update_vindi_subscription($new_status, $old_status)
    {
        $last_order_id = $this->wc_subscription->get_last_order();
        $last_order    = wc_get_order($last_order_id);

        if('active' === $old_status && 'on-hold' === $new_status && false === $last_order->needs_payment()) {
            $this->container->api->delete_subscription($this->vindi_subscription_id);
            return ;
        }

        if('pendding' === $old_status && 'on-hold' === $new_status) {
            $this->container->api->delete_subscription($this->vindi_subscription_id);
            return ;
        }

        if('cancelled' === $new_status) {
            $this->container->api->delete_subscription($this->vindi_subscription_id, true);
            return ;
        }
    }
}
