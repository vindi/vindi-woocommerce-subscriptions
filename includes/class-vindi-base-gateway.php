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
        }
    }

    /**
     * Admin Panel Options
     */
    public function admin_options()
    {
        include_once(sprintf('%s/%s', Vindi_WooCommerce_Subscriptions::VIEWS_DIR, 'admin-gateway-settings.html.php'));
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
            return $this->container->woocommerce->customer->get_country();
        }
    }

    /**
     * Validate plugin settings
     * @return bool
     */
    public function validate_settings()
    {
        $currency = get_option('woocommerce_currency');
        return in_array($currency, ['BRL']) && ! empty($this->container->get_api_key());
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
        $this->container->logger->log(sprintf('Processando pedido %s.', $order_id));
        $order   = wc_get_order($order_id);
        $payment = new Vindi_Payment($order, $this, $this->container);

        // exit if validation by validate_fields() fails
        if (! $this->validated) {
            return false;
        }

        // Validate plugin settings
        if (! $this->validate_settings()) {
            return $payment->abort(__('O Pagamento foi cancelado devido a erro de configuração do meio de pagamento.', VINDI_IDENTIFIER));
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
