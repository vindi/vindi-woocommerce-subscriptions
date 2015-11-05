<?php

abstract class Vindi_Base_Gateway extends WC_Payment_Gateway
{
    /**
     * @var bool
     */
    protected $validated = true;

    /**
     * @var Vindi_Settings
     */
    public $container;

    /**
     * Should return payment type for payment processing.
     * @return string
     */
    public abstract function type();

    public function __construct(Vindi_Settings $container)
    {
        $this->container = $container;
        $this->title     = $this->get_option('title');
        $this->enabled   = $this->get_option('enabled');

        if (is_admin()) {
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
            add_action('add_meta_boxes_shop_order', array(&$this, 'vindi_order_metabox'));
            add_filter('product_type_selector', array(&$this, 'vindi_subscription_product_type'));
            add_action('save_post', array(&$this, 'vindi_save_subscription_meta'));
        }
    }

    /**
     * Admin Panel Options
     * @return void
     */
    public function admin_options()
    {
        include_once(sprintf('%s/%s', Vindi_WooCommerce_Subscriptions::VIEWS_DIR, 'admin-gateway-settings.html.php'));
    }

    /**
     * @param int $post_id
     */
    public function vindi_save_subscription_meta($post_id)
    {
        if (! isset($_POST['product-type']) || ('vindi-subscription' !== $_POST['product-type'])) {
            return;
        }

        $subscription_price = stripslashes($_REQUEST['vindi_subscription_price']);
        $subscription_plan  = (int) stripslashes($_REQUEST['vindi_subscription_plan']);

        update_post_meta($post_id, 'vindi_subscription_price', $subscription_price);
        update_post_meta($post_id, '_regular_price', $subscription_price);
        update_post_meta($post_id, '_price', $subscription_price);

        update_post_meta($post_id, 'vindi_subscription_plan', $subscription_plan);
    }

    /**
     * Get the users country either from their order, or from their customer data
     * @return string|null
     */

    public function get_country_code()
    {
        if (isset($_GET['order_id'])) {
            $order = new WC_Order($_GET['order_id']);
            return $order->billing_country;
        } elseif ($this->container->woocommerce->customer->get_country()) {
            return $woocommerce->customer->get_country();
        }

        return null;
    }

    /**
     * @param $types
     *
     * @return mixed
     */

    public function vindi_subscription_product_type($types)
    {
        $types['vindi-subscription'] = __('Assinatura Vindi', 'woocommerce-vindi');
        return $types;
    }

    /**
     * Create Vindi Order Meta Box
     * @return void
     */

    public function vindi_order_metabox()
    {
        add_meta_box('vindi-wc-subscription-meta-box',
            __('Assinatura Vindi','woocommerce-vindi'),
                array(&$this, 'vindi_order_metabox_content'),
                'shop_order',
                'normal',
                'default'
            );
    }

    /**
     * Validate plugin settings
     * @return bool
     */

    public function validate_settings()
    {
        $currency = get_option('woocommerce_currency');
        return in_array($currency, ['BRL']) && ! empty($this->apiKey);
    }

    /**
     * Process the payment
     *
     * @param int $order_id
     *
     * @return array
     */

    public function process_payment($order_id)
    {
        $this->log(sprintf('Processando pedido %s.', $order_id));
        $order   = new WC_Order($order_id);
        $payment = new WC_Vindi_Payment($order, $this);

        // exit if validation by validate_fields() fails
        if (! $this->validated) {
            return false;
        }

        // Validate plugin settings
        if (! $this->validate_settings()) {
            return $payment->abort(__('O Pagamento foi cancelado devido a erro de configuração do meio de pagamento.', 'woocommerce-vindi'));
        }

        try {
            $response = $payment->process();
            $order->reduce_order_stock();
        } catch (Exception $e) {
            $response = array(
                'result'   => 'fail',
                'redirect' => '',
            );
        }

        return $response;
    }

    /**
     * WC Get Template helper.
     *
     * @param       $name
     * @param array $args
     */
    protected function get_template($name, $args = [])
    {
        wc_get_template($name, $args, '', Vindi_WooCommerce_Subscriptions::VIEWS_DIR . 'templates/');
    }

    /**
     * Check if the order is a Single Payment Order (not a Subscription).
     * @return bool
     */

    protected function is_single_order()
    {
        $items = $this->container->woocommerce->cart->cart_contents;

        foreach ($items as $item) {
            if ('vindi-subscription' === $item['data']->product_type) {
                return false;
            }
        }

        return true;
    }
}
