<?php
class Vindi_Payment
{
    /**
     * Order type is invalid.
     */
    const ORDER_TYPE_INVALID = 0;

    /**
     * Order type is Subscription Payment.
     */
    const ORDER_TYPE_SUBSCRIPTION = 1;

    /**
     * Order type is Single Payment.
     */
    const ORDER_TYPE_SINGLE = 2;

    /**
     * Order that will be paid;
     * @var WC_Order
     */
    protected $order;

    /**
     * Vindi Gateway.
     * @var Vindi_Settings
     */
    protected $container;

    /**
     * @param WC_Order           $order
     * @param Vindi_Base_Gateway $gateway
     * @param Vindi_Settings     $container
     */
    function __construct(WC_Order $order, Vindi_Base_Gateway $gateway, Vindi_Settings $container)
    {
        $this->order     = $order;
        $this->gateway   = $gateway;
        $this->container = $container;
    }

    /**
     * Validate order to chose payment type.
     * @return int order type.
     */

    public function get_order_type()
    {
        $items = $this->order->get_items();

        foreach ($items as $item) {
            $product = $this->order->get_product_from_item($item);
            if($this->is_subscription_type($product))
                return static::ORDER_TYPE_SUBSCRIPTION;
        }

        return static::ORDER_TYPE_SINGLE;
    }

    /**
     * Retrieve Plan for Vindi Subscription.
     * @return int|bool
     */
    public function get_plan()
    {
        $items = $this->order->get_items();

        foreach($items as $item) {
            $product    = $this->order->get_product_from_item($item);
            $vindi_plan = get_post_meta($product->id, 'vindi_subscription_plan', true);

            if ($this->is_subscription_type($product) AND !empty($vindi_plan))
                return $vindi_plan;
        }

        $this->abort(__('O produto selecionado não é uma assinatura.', VINDI_IDENTIFIER), true);
    }

    /**
     * Find or Create a Customer at Vindi for the given credentials.
     * @return array|bool
     */
    public function get_customer()
    {
        $currentUser = wp_get_current_user();
        $email       = $this->order->get_billing_email();

        $address = array(
            'street'             => $this->order->get_billing_address_1(),
            'number'             => $this->order->get_meta( '_billing_number' ),
            'additional_details' => $this->order->get_billing_address_2(),
            'zipcode'            => $this->order->get_billing_postcode(),
            'neighborhood'       => $this->order->get_meta( '_billing_neighborhood' ),
            'city'               => $this->order->get_billing_city(),
            'state'              => $this->order->get_billing_state(),
            'country'            => $this->order->get_billing_country(),
        );

        $user_id = $currentUser->ID;

        if (! $user_code = get_user_meta($user_id, 'vindi_user_code', true)) {
            $user_code = 'wc-' . $user_id . '-' . time();
            add_user_meta($user_id, 'vindi_user_code', $user_code, true);
        }

        $metadata = array();

        if ('2' === $this->order->get_meta( '_billing_persontype' )) {
            // Pessoa jurídica
            $name        = $this->order->get_billing_company();
            $cpf_or_cnpj = $this->order->get_meta( '_billing_cnpj' );
            $notes       = sprintf('Nome: %s %s', $this->order->get_billing_first_name(), $this->order->get_billing_last_name());

            if ($this->container->send_nfe_information())
                $metadata['inscricao_estadual'] = $this->order->get_meta( '_billing_ie' );

        } else {
            // Pessoa física
            $name        = $this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name();
            $cpf_or_cnpj = $this->order->get_meta( '_billing_cpf' );
            $notes       = '';

            if ($this->container->send_nfe_information())
                $metadata['carteira_de_identidade'] = $this->order->get_meta( '_billing_rg' );
        }

        $phones = array_filter(array_map(
            [$this, 'format_phone'],
            [
                $this->order->get_billing_phone(),
                $this->order->get_meta( '_billing_cellphone' ),
            ]
        ));

        $customer = array(
            'name'          => $name,
            'email'         => $email,
            'registry_code' => $cpf_or_cnpj,
            'code'          => $user_code,
            'address'       => $address,
            'notes'         => $notes,
            'phones'        => $phones,
            'metadata' => $metadata,
        );

        $customer_id = $this->container->api->find_or_create_customer($customer);

        if(!$this->container->api->update_customer_phone($customer_id, $phone_number)) {
            $this->abort(__('Falha ao registrar o usuário. Verifique os dados e tente novamente.', VINDI_IDENTIFIER ), true);
        }

        if (false === $customer_id) {
            $this->abort(__('Falha ao registrar o usuário. Verifique os dados e tente novamente.', VINDI_IDENTIFIER ), true);
        }

        $this->container->logger->log(sprintf('Cliente Vindi: %s', $customer_id));

        if ($this->is_cc())
            $this->create_payment_profile($customer_id);

        return $customer_id;
    }

    /**
     * @param array Customer phones $phone
     * @return array
     */
    public function format_phone($phone)
    {
        $phone = preg_replace('/\D+/', '', '55'. $phone);

        switch(strlen($phone)) {
            case 12:
                $phone_type = 'landline';
                break;
            case 13:
                $phone_type = 'mobile';
                break;
        }

        if (isset($phone_type)) {
            return [
                'phone_type' => $phone_type,
                'number'     => $phone
            ];
        }
    }

    /**
     * Build payment type for credit card.
     *
     * @param int $customer_id
     *
     * @return array
     */
    public function get_cc_payment_type($customer_id)
    {
        if($this->gateway->verify_user_payment_profile())
            return false;

        return array(
            'customer_id'           => $customer_id,
            'holder_name'           => $_POST['vindi_cc_fullname'],
            'card_expiration'       => $_POST['vindi_cc_monthexpiry'] . '/' . $_POST['vindi_cc_yearexpiry'],
            'card_number'           => $_POST['vindi_cc_number'],
            'card_cvv'              => $_POST['vindi_cc_cvc'],
            'payment_method_code'   => $this->payment_method_code(),
            'payment_company_code'  => $_POST['vindi_cc_paymentcompany'],
        );
    }

    /**
     * Check if payment is of type "Credit Card"
     * @return bool
     */
    public function is_cc()
    {
        return 'cc' === $this->gateway->type();
    }

    /**
     * Check if payment is of type "Invoice"
     * @return bool
     */
    public function is_invoice()
    {
        return 'invoice' === $this->gateway->type();
    }

    /**
     * @return string
     */
    public function payment_method_code()
    {
        // TODO fix it to proper method code
        return $this->is_cc() ? 'credit_card' : 'bank_slip';
    }

    /**
     * @param string $message
     * @param bool   $throw_exception
     *
     * @return bool
     * @throws Exception
     */
    public function abort($message, $throw_exception = false)
    {
        $this->container->logger->log($message);
        $this->order->add_order_note($message);
        wc_add_notice($message, 'error');
        if ($throw_exception)
            throw new Exception($message);

        return false;
    }

    /**
     * @return array|void
     * @throws Exception
     */
    public function process()
    {
        switch ($orderType = $this->get_order_type()) {
            case static::ORDER_TYPE_SINGLE:
                return $this->process_single_payment();
            case static::ORDER_TYPE_SUBSCRIPTION:
                return $this->process_subscription();
            case static::ORDER_TYPE_INVALID:
            default:
                return $this->abort(__('Falha ao processar carrinho de compras. Verifique os itens escolhidos e tente novamente.', VINDI_IDENTIFIER), true);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function process_subscription()
    {
        $customer_id      = $this->get_customer();
        $subscription     = $this->create_subscription($customer_id);
        $wc_subscriptions = wcs_get_subscriptions_for_order($this->order);
        $wc_subscription  = end($wc_subscriptions);

        add_post_meta($this->order->id, 'vindi_wc_cycle', $subscription['current_period']['cycle']);
        add_post_meta($this->order->id, 'vindi_wc_subscription_id', $subscription['id']);
        add_post_meta($this->order->id, 'vindi_wc_bill_id', $subscription['bill']['id']);
        add_post_meta($wc_subscription->id, 'vindi_wc_subscription_id', $subscription['id']);

        if ($message = $this->cancel_if_denied_bill_status($subscription['bill'])) {
            $wc_subscription->update_status('cancelled', __($message, VINDI_IDENTIFIER));
            $this->order->update_status('cancelled', __($message, VINDI_IDENTIFIER));
            $this->abort(__($message, VINDI_IDENTIFIER), true);
        }

        $this->add_download_url_meta_for_subscription($subscription);

        remove_action( 'woocommerce_scheduled_subscription_payment', 'WC_Subscriptions_Manager::prepare_renewal' );

        return $this->finish_payment($subscription['bill']);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function process_single_payment()
    {
        $customer_id = $this->get_customer();
        $bill        = $this->create_bill($customer_id);

        if($message = $this->cancel_if_denied_bill_status($bill)) {
            $this->container->api->delete_bill($bill['id']);
            $this->order->update_status('cancelled', __($message, VINDI_IDENTIFIER));
            $this->abort(__($message, VINDI_IDENTIFIER), true);
        }

        add_post_meta($this->order->id, 'vindi_wc_bill_id', $bill['id']);
        $this->add_download_url_meta_for_single_payment($bill['id']);

        return $this->finish_payment($bill);
    }

    /**
     * @param int $customer_id
     *
     * @throws Exception
     */
    protected function create_payment_profile($customer_id)
    {
        $cc_info = $this->get_cc_payment_type($customer_id);

        if(false === $cc_info)
            return ;

        $payment_profile_id = $this->container->api->create_customer_payment_profile($cc_info);
        if (false === $payment_profile_id)
            $this->abort(__('Falha ao registrar o método de pagamento. Verifique os dados e tente novamente.', VINDI_IDENTIFIER), true);
    }

    /**
     * @param array $item
     **/
  private function return_cycle_from_product_type($item)
    {
        if ($item['type'] == 'shipping')
            return null;
        
        if(!$this->is_subscription_type($item->get_product())) {
            return 1;
        }

        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function build_product_items($order_type = 'bill')
    {
        $call_build_items = "build_product_items_for_{$order_type}";

        if(false === method_exists($this, $call_build_items)) {
            $this->abort(__("Ocorreu um erro ao gerar o seu pedido!", VINDI_IDENTIFIER), true);
        }

        $product_items  = [];
        $order_items    = $this->build_product_order_items();
        $order_items[]  = $this->build_shipping_item();

        if('bill' === $order_type) {
            $order_items[] = $this->build_discount_item_for_bill();
        }


        foreach ($order_items as $order_item) {
            if($item = $this->$call_build_items($order_item)) {
                $product_items[] = $item;
            }
        }

        if (empty($product_items)) {
            return $this->abort(__('Falha ao recuperar informações sobre o produto na Vindi. Verifique os dados e tente novamente.', VINDI_IDENTIFIER), true);
        }

        return $product_items;
    }

    protected function build_product_order_items()
    {
        $order_items = $this->order->get_items();

        foreach ($order_items as $key => $order_item) {
            $product                       = $this->get_product($order_item);
            $order_items[$key]['type']     = 'product';
            $order_items[$key]['vindi_id'] = $product->vindi_id;
            $order_items[$key]['price']    = $order_item['total'];
        }

        return $order_items;
    }

    protected function build_shipping_item()
    {
        $shipping_item   = [];
        $shipping_method = $this->order->get_shipping_method();

        if(empty($shipping_method))
            return $shipping_item;

        $item          = $this->container->api->find_or_create_product("Frete ($shipping_method)", sanitize_title($shipping_method));
        $shipping_item = array(
            'type'     => 'shipping',
            'vindi_id' => $item['id'],
            'price'    => (float) $this->order->get_total_shipping(),
            'qty'      => 1,
        );

        return $shipping_item;
    }

    protected function build_discount_item_for_bill()
    {
        $discount_item  = [];
        $total_discount = $this->order->get_total_discount();

        if(empty($total_discount)) {
            return $discount_item;
        }

        $item          = $this->container->api->find_or_create_product("Cupom de desconto", 'wc-discount');
        $discount_item = array(
            'type'     => 'discount',
            'vindi_id' => $item['id'],
            'price'    => (float) $total_discount * -1,
            'qty'      => 1
        );

        return $discount_item;
    }

    protected function build_product_items_for_bill($order_item)
    {
        if(empty($order_item)) {
            return false;
        }

        $item = [
            'product_id'        => $order_item['vindi_id'],
            'quantity'          => $order_item['qty'],
            'pricing_schema'    => [
                'price'             => $order_item['price'],
                'schema_type'       => 'per_unit'
            ]
        ];

        if($order_item['type'] == 'discount') {
            $item = [
                'product_id'        => $order_item['vindi_id'],
                'amount'            => $order_item['price']
            ];
        }

        return $item;
    }

    protected function build_product_items_for_subscription($order_item)
    {
        if(empty($order_item)) {
            return false;
        }


        $total_discount = $this->order->get_total_discount();
        $coupons_cycles  = $this->container->cycles_to_discount();
       

        if(empty($coupons_cycles)) {
            $discount_cycles = $coupons_cycles;
        } else {
            $vindi_plan_id   = $this->get_plan();
            $plan_cycles     = $this->container->api->get_plan_billing_cycles($vindi_plan_id);

            if ($plan_cycles == 0) {
                $discount_cycles = $coupons_cycles;
            } else {
                $discount_cycles = min($plan_cycles, $coupons_cycles);
            }
        }
        
        $product_item =  array(
            'product_id'      => $order_item['vindi_id'],
            'quantity'        => $order_item['qty'],
            'cycles'          => $this->return_cycle_from_product_type($order_item),
            'pricing_schema'  => array(
                'price'       => $order_item['price'],
                'schema_type' => 'per_unit'
            )
        );
        
        if(!empty($total_discount) && $order_item['type'] == 'line_item') {
            $order_subtotal      = $this->order->get_subtotal();
            $discount_percentage = ($total_discount / $order_subtotal) * 100;
            $product_item['discounts']  = array(
                array(
                    'discount_type' => 'percentage',
                    'percentage'    => $discount_percentage,
                    'cycles'        => $discount_cycles
                )
            );

        }
        
        return $product_item;
    }

    /**
     * @return int
     */
    protected function installments()
    {
        if('credit_card' == $this->payment_method_code() && ! is_null($_POST['vindi_cc_installments']))
            return $_POST['vindi_cc_installments'];

        return 1;
    }

    /**
     * @param $customer_id
     *
     * @return array
     * @throws Exception
     */
    protected function create_subscription($customer_id)
    {
        $vindi_plan            = $this->get_plan();
        $wc_subscription_array = wcs_get_subscriptions_for_order($this->order->id);
        $wc_subscription       = end($wc_subscription_array);

        $body = array(
            'customer_id'         => $customer_id,
            'payment_method_code' => $this->payment_method_code(),
            'plan_id'             => $vindi_plan,
            'product_items'       => $this->build_product_items('subscription'),
            'code'                => $wc_subscription->id,
            'installments'        => $this->installments()
        );

        $subscription = $this->container->api->create_subscription($body);

        if (! isset($subscription['id']) || empty($subscription['id'])) {
            $this->container->logger->log(sprintf('Erro no pagamento do pedido %s.', $this->order->id));

            $message = sprintf(__('Pagamento Falhou. (%s)', VINDI_IDENTIFIER), $this->container->api->last_error);
            $this->order->update_status('failed', $message);

            throw new Exception($message);
        }

        return $subscription;
    }

    /**
     * @param int $customer_id
     *
     * @return int
     * @throws Exception
     */
    protected function create_bill($customer_id)
    {
        $body = array(
            'customer_id'         => $customer_id,
            'payment_method_code' => $this->payment_method_code(),
            'bill_items'          => $this->build_product_items('bill'),
            'code'                => $this->order->id,
            'installments'        => $this->installments()
        );

        $bill_id = $this->container->api->create_bill($body);

        if (! $bill_id) {
            $this->container->logger->log(sprintf('Erro no pagamento do pedido %s.', $this->order->id));
            $message = sprintf(__('Pagamento Falhou. (%s)', VINDI_IDENTIFIER), $this->container->api->last_error);
            $this->order->update_status('failed', $message);

            throw new Exception($message);
        }

        return $bill_id;
    }

    /**
     * @param $subscription
     */
    protected function add_download_url_meta_for_subscription($subscription)
    {
        if (isset($subscription['bill'])) {
            $bill         = $subscription['bill'];
            $download_url = false;

            if ('review' === $bill['status']) {
                $this->container->api->approve_bill($bill['id']);
                $download_url = $this->container->api->get_bank_slip_download($bill['id']);
            } elseif (isset($bill['charges']) && count($bill['charges'])) {
                $download_url = $bill['charges'][0]['print_url'];
            }

            if ($download_url)
                add_post_meta($this->order->id, 'vindi_wc_invoice_download_url', $download_url);
        }
    }

    /**
     * @param int $bill_id
     */
    protected function add_download_url_meta_for_single_payment($bill_id)
    {
        $download_url = false;

        if ($this->container->api->approve_bill($bill_id))
            $download_url = $this->container->api->get_bank_slip_download($bill_id);

        if ($download_url)
            add_post_meta($this->order->id, 'vindi_wc_invoice_download_url', $download_url);
    }

    protected function cancel_if_denied_bill_status($bill)
    {
        if(empty($bill['charges'])) {
            return false;
        }

        $last_charge        = end($bill['charges']);
        $transaction_status = $last_charge['last_transaction']['status'];
        $denied_status      = [
            'rejected' => 'Infelizmente não foi possível autorizar seu pagamento.',
            'failure'  => 'Ocorreu um erro ao aprovar a transação, tente novamente.'
        ];

        if(array_key_exists($transaction_status, $denied_status)) {
            return $denied_status[$transaction_status];
        }

        return false;
    }

    /**
     * @return array
     */
    protected function finish_payment($bill)
    {
        $this->container->woocommerce->cart->empty_cart();

        if($bill['status'] == 'paid') {
            $status         = $this->container->get_return_status();
            $status_message = __('O Pagamento foi realizado com sucesso pela Vindi.', VINDI_IDENTIFIER);
        } else {
            $data_to_log    = sprintf('Aguardando pagamento do pedido %s pela Vindi.', $this->order->id);
            $status_message = __('Aguardando pagamento do pedido.', VINDI_IDENTIFIER);
            $status         = 'pending';
        }

        $this->container->logger->log($data_to_log);
        $this->order->update_status($status, $status_message);

        return array(
            'result'   => 'success',
            'redirect' => $this->order->get_checkout_order_received_url(),
        );
    }

    /**
     * @param array $product
     **/
    protected function get_product($order_item)
    {
        $product       = $this->order->get_product_from_item($order_item);
        $product_title = $product->get_title();

        if($this->is_variable($product)) {
            $variations    = $this->parse_variation_name($product->get_attributes(), $order_item);
            $product_title = sprintf("%s (%s)", $product_title, $variations);
        }

        $item = $this->container->api->find_or_create_product(
            $product_title, sanitize_title($product_title)
        );

        $product->vindi_id = (int) $item['id'];

        return $product;

    }

    protected function parse_variation_name($attributes, $order_item)
    {
        $keys  = array_keys($attributes);
        $names = [];

        foreach($order_item['item_meta'] as $key => $meta) {
            if(in_array($key, $keys)) {
                $names[] = $meta;
            }
        }

        return join(' - ', $names);
    }

    protected function is_variable($product)
    {
        return (boolean) preg_match('/variation/', $product->get_type());
    }

    protected function is_subscription_type(WC_Product $product)
    {
        return (boolean) preg_match('/subscription/', $product->get_type());
    }
}
