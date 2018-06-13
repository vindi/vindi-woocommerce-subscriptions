<?php

class Vindi_API
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    public $last_error = '';

    /**
     * @var bool
     */
    private $accept_bank_slip;

    /**
     * @var Vindi_Logger
     */
    private $logger;

    /**
     * @var String 'Yes' or 'no'
     */
    private $sandbox;

    private $errors_list = array(
        'invalid_parameter|card_number'          => 'Número do cartão inválido.',
        'invalid_parameter|registry_code'        => 'CPF ou CNPJ Invalidos',
        'invalid_parameter|payment_company_code' => 'Método de pagamento Inválido',
        'invalid_parameter|payment_company_id'   => 'Método de pagamento Inválido',
        'invalid_parameter|phones.number'        => 'Número de telefone inválido',
        'invalid_parameter|phones'               => 'Erro ao cadastrar o telefone'
    );

    /**
     * API Base path
     *
     * @return string
     */
    public function base_path()
    {
        if('yes' === $this->sandbox) {
            return 'https://sandbox-app.vindi.com.br/api/v1/';
        }

        return 'https://app.vindi.com.br/api/v1/';
    }

    /**
     * @param string $key
     * @param Vindi_Logger $logger
     * @param string $sandbox
     */
    public function __construct($key, Vindi_Logger $logger, $sandbox)
    {
        $this->key          = $key;
        $this->logger       = $logger;
        $this->sandbox      = $sandbox;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    private function build_body($data)
    {
        $body = null;

        if (!empty($data)) {
            $body = json_encode($data);
        }

        return $body;
    }

    /**
     * Generate Authentication Header.
     * @return string
     */
    private function get_auth_header()
    {
        return sprintf('Basic %s', base64_encode($this->key . ":"));
    }

    /**
     * @param array $error
     * @param       $endpoint
     *
     * @return string
     */
    private function get_error_message($error, $endpoint)
    {
        $error_id         = empty($error['id']) ? '' : $error['id'];
        $error_parameter  = empty($error['parameter']) ? '' : $error['parameter'];

        $error_identifier = sprintf('%s|%s', $error_id, $error_parameter);

        if(false === array_key_exists($error_identifier, $this->errors_list))
            return $error_identifier;

        return __($this->errors_list[$error_identifier], VINDI_IDENTIFIER);
    }

    /**
     * @param array $response
     * @param       $endpoint
     *
     * @return bool
     */
    private function check_response($response, $endpoint)
    {
        if (isset($response['errors']) && ! empty($response['errors'])) {
            foreach ($response['errors'] as $error) {
                $message = $this->get_error_message($error, $endpoint);

                if (function_exists('wc_add_notice')) {
                    wc_add_notice(__($message, VINDI_IDENTIFIER), 'error');
                }

                $this->last_error = $message;
            }

            return false;
        }

        $this->last_error = '';

        return true;
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array  $data
     * @param null   $data_to_log
     *
     * @return array|bool|mixed
     */
    private function request($endpoint, $method = 'POST', $data = array(), $data_to_log = null)
    {
        $url  = sprintf('%s%s', $this->base_path(), $endpoint);
        $body = $this->build_body($data);

        $request_id = rand();

        $data_to_log = null !== $data_to_log ? $this->build_body($data_to_log) : $body;

        $this->logger->log(sprintf("[Request #%s]: Novo Request para a API.\n%s %s\n%s", $request_id, $method, $url, $data_to_log));

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => $this->get_auth_header(),
                'Content-Type'  => 'application/json',
                'User-Agent'    => sprintf('Vindi-WooCommerce-Subscriptions/%s; %s', Vindi_WooCommerce_Subscriptions::VERSION, get_bloginfo( 'url' )),
            ),
            'method'    => $method,
            'timeout'   => 60,
            'sslverify' => true,
            'body'      => $body,
        ));

        if (is_wp_error($response)) {
            $this->logger->log(sprintf("[Request #%s]: Erro ao fazer request! %s", $request_id, print_r($response, true)));

            return false;
        }

        $status = sprintf('%s %s', $response['response']['code'], $response['response']['message']);
        $this->logger->log(sprintf("[Request #%s]: Nova Resposta da API.\n%s\n%s", $request_id, $status, print_r($response['body'], true)));

        $response_body = wp_remote_retrieve_body($response);

        if (! $response_body) {
            $this->logger->log(sprintf('[Request #%s]: Erro ao recuperar corpo do request! %s', $request_id, print_r($response, true)));

            return false;
        }

        $response_body_array = json_decode($response_body, true);

        if (! $this->check_response($response_body_array, $endpoint)) {
            return false;
        }

        return $response_body_array;
    }

    /**
     * @param array $body (name, email, code)
     *
     * @return array|bool|mixed
     */
    public function create_customer($body)
    {
        if ($response = $this->request('customers', 'POST', $body)) {
            return $response['customer']['id'];
        }

        return false;
    }

    /**
     * @param int   $subscription_id
     *
     * @return array|bool|mixed
     */
    public function suspend_subscription($subscription_id, $cancel_bills = false)
    {
        $query = '';

        if(!$cancel_bills)
            $query = '?cancel_bills=false';

        if ($response = $this->request('subscriptions/' . $subscription_id . $query, 'DELETE'))
            return $response;

        return false;
    }

    /**
     * @param int   $subscription_id
     *
     * @return array|bool|mixed
     */
    public function activate_subscription($subscription_id)
    {
        if ($response = $this->request('subscriptions/' . $subscription_id . '/reactivate', 'POST'))
            return $response;

        return false;
    }

    /**
     * @param int   $bill_id
     *
     * @return array|bool|mixed
     */
    public function delete_bill($bill_id)
    {
        if ($response = $this->request('bills/' . $bill_id, 'DELETE'))
            return $response;

        return false;
    }

    /**
     * @param string $code
     *
     * @return array|bool|mixed
     */
    public function find_customer_by_code($code)
    {
        $response = $this->request(sprintf(
            'customers/search?code=%s',
            $code
        ),'GET');

        if ($response && (1 === count($response['customers'])) &&
            isset($response['customers'][0]['id'])) {
            return $response['customers'][0]['id'];
        }

        return false;
    }

    /**
     * @param array $body (name, email, code)
     *
     * @return array|bool|mixed
     */
    public function find_or_create_customer($body)
    {
        $customer_id = $this->find_customer_by_code($body['code']);

        if (false === $customer_id) {
            return $this->create_customer($body);
        }

        return $customer_id;
    }


    /**
     * @return array|bool|mixed
     */
    public function get_payment_profile($user_code)
    {
        $customer = $this->find_customer_by_code($user_code);

        if(empty($customer))
            return false;

        $query    = urlencode("customer_id={$customer} status=active type=PaymentProfile::CreditCard");
        $response = $this->request('payment_profiles?query='.$query, 'GET');

        if(isset($response['payment_profiles'][0]))
            return $response['payment_profiles'][0];

        return false;
    }

    /**
     * @param $body (holder_name, card_expiration, card_number, card_cvv, customer_id)
     *
     * @return array|bool|mixed
     */
    public function create_customer_payment_profile($body)
    {
        // Protect credit card number.
        $log                = $body;
        $log['card_number'] = '**** *' . substr($log['card_number'], -3);
        $log['card_cvv']    = '***';

        return $this->request('payment_profiles', 'POST', $body, $log);
    }

    /**
     * @param $body (plan_id, customer_id, payment_method_code, product_items[{product_id}])
     *
     * @return array
     */
    public function create_subscription($body)
    {
        if (($response = $this->request('subscriptions', 'POST', $body)) &&
            isset($response['subscription']['id'])) {

            $subscription         = $response['subscription'];
            $subscription['bill'] = $response['bill'];

            return $subscription;
        }

        return false;
    }

    /**
     * @return array|bool
     */
    public function get_payment_methods()
    {
        if (false === ($payment_methods = get_transient('vindi_payment_methods'))) {

            $payment_methods = array(
                'credit_card' => array(),
                'bank_slip'   => false,
            );

            $response = $this->request('payment_methods', 'GET');

            if (false === $response)
                return false;

            foreach ($response['payment_methods'] as $method) {
                if ('active' !== $method['status']) {
                    continue;
                }

                if ('PaymentMethod::CreditCard' === $method['type']) {
                    $payment_methods['credit_card'] = array_merge(
                        $payment_methods['credit_card'],
                        $method['payment_companies']
                    );
                } else if ('PaymentMethod::BankSlip' === $method['type']) {
                    $payment_methods['bank_slip'] = true;
                }
            }

            set_transient('vindi_payment_methods', $payment_methods, 1 * HOUR_IN_SECONDS);
        }

        $this->accept_bank_slip = $payment_methods['bank_slip'];

        return $payment_methods;
    }

    /**
     * @param string $code
     *
     * @return array|bool|mixed
     */
    public function update_customer_phone($id_customer, $phone_number)
    {
        $response = $this->request(sprintf('customers/%s', $id_customer),'GET');

        if(empty($response['customer'])) {
            return false;
        }

        if(empty($response['customer']['phones'])) {
            $phone_data = [
                'phones' => $phone_number
            ];

            return (boolean) $this->request(sprintf('customers/%s', $id_customer),'PUT', $phone_data);
        }

        return true;
    }

    /**
     * @return bool|null
     */
    public function accept_bank_slip()
    {
        if (null === $this->accept_bank_slip) {
            $this->get_payment_methods();
        }

        return $this->accept_bank_slip;
    }

    /**
     * @param array $body
     *
     * @return int|bool
     */
    public function create_bill($body)
    {
        if ($response = $this->request('bills', 'POST', $body)) {
            return $response['bill'];
        }

        return false;
    }

    /**
     * @param $bill_id
     *
     * @return array|bool|mixed
     */
    public function approve_bill($bill_id)
    {
        $response = $this->request(sprintf('bills/%s', $bill_id), 'GET');

        if (false === $response || ! isset($response['bill'])) {
            return false;
        }

        $bill = $response['bill'];

        if ('review' !== $bill['status']) {
            return true;
        }

        return $this->request(sprintf('bills/%s/approve', $bill_id), 'POST');
    }

    /**
     * @param $bill_id
     *
     * @return string
     */
    public function get_bank_slip_download($bill_id)
    {
        $response = $this->request(sprintf('bills/%s', $bill_id), 'GET');

        if (false === $response) {
            return false;
        }

        return $response['bill']['charges'][0]['print_url'];
    }

    /**
     * @return array
     */
    public function get_products()
    {
        $list     = array();
        $response = $this->request('products?query=status:active', 'GET');

        if ($products = $response['products']) {
            foreach ($products as $product) {
                $list[$product['id']] = sprintf('%s (%s)', $product['name'], $product['pricing_schema']['short_format']);
            }
        }

        return $list;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function get_plan_items($id)
    {
        $list     = array();
        $response = $this->request(sprintf('plans/%s', $id), 'GET');

        if ($plan = $response['plan']) {
            foreach ($plan['plan_items'] as $item) {
                if (isset($item['product'])) {
                    $list[] = $item['product']['id'];
                }
            }
        }

        return $list;
    }

    /**
     * @param int   $plan_id
     * @param float $order_total
     *
     * @return array
     */
    public function build_plan_items_for_subscription($plan_id, $order_total)
    {
        $list = array();

        foreach ($this->get_plan_items($plan_id) as $item) {
            $list[] = array(
                'product_id'     => $item,
                'pricing_schema' => array(
                    'price' => $order_total
                ),
            );
            $order_total = 0;
        }

        return $list;
    }

    /**
     * @return array
     */
    public function get_plans()
    {
        $list = array(
            'names' => array(),
            'infos' => array()
        );

        $plans    = [];
        $page     = 1;
        $per_page = 50;

        do{
            $response = $this->request('plans?query=status:active&per_page=' . $per_page . '&page=' . $page, 'GET');
            $plans    = array_merge($plans, $response['plans']);
            $page++;
        } while(count($response['plans']) >= $per_page);

        if (false == empty($plans)) {
            foreach ($plans as $plan) {
                $list['names'][$plan['id']] = $plan['name'];
                $list['infos'][$plan['id']] = array(
                    'name'                 => $plan['name'],
                    'interval'             => $plan['interval'],
                    'interval_count'       => $plan['interval_count'],
                    'billing_trigger_type' => $plan['billing_trigger_type'],
                    'billing_trigger_day'  => $plan['billing_trigger_day'],
                    'billing_cycles'       => $plan['billing_cycles'],
                );
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    public function get_plan($id)
    {
        $response = $this->request('plans/' . $id, 'GET');

        if (empty($response['plan'])) {
            return false;
        }

        return $response['plan'];
    }

    /**
     * @return int|bool|mixed
     */
    public function get_plan_billing_cycles($plan_id)
    {
        $plan = $this->get_plan($plan_id);

        if(empty($plan)) {
            return false;
        }

        return (int) $plan['billing_cycles'];

    }

    /**
     * @return int|bool|mixed
     */
    public function get_plan_installments($plan_id)
    {
        $plan = $this->get_plan($plan_id);

        if(empty($plan)) {
            return false;
        }

        return (int) $plan['installments'];
    }

    /**
     * @param array $body (name, code, status, pricing_schema (price))
     *
     * @return array|bool|mixed
     */
    public function create_product($body)
    {
        if ($response = $this->request('products', 'POST', $body)) {
            return $response['product'];
        }

        return false;
    }

    /**
     * @param string $code
     *
     * @return array|bool|mixed
     */
    public function find_product_by_code($code)
    {
        $transient_key = 'vindi_product_' . $code;
        $product       = get_transient($transient_key);

        if(false !== $product)
            return $product;

        $response = $this->request(sprintf('products?query=code:%s', $code), 'GET');

        if (false === empty($response['products'])) {
            $product = end($response['products']);
            set_transient($transient_key, $product, 1 * HOUR_IN_SECONDS);
        }

        return $product;
    }

    /**
     * @param string $name
     * @param string $code
     *
     * @return array
     */
    public function find_or_create_product($name, $code)
    {
        $product = $this->find_product_by_code($code);

        if (false === $product)
        {
            return $this->create_product(array(
                'name'           => $name,
                'code'           => $code,
                'status'         => 'active',
                'pricing_schema' => array(
                    'price' => 0,
                ),
            ));
        }

        return $product;
    }

    /**
     * Make an API request to retrieve informations about the Merchant.
     * @return array|bool|mixed
     */
    public function get_merchant()
    {
        if (false === ($merchant = get_transient('vindi_merchant'))) {
            $response = $this->request('merchant', 'GET');

            if (! $response || ! $response['merchant'])
                return false;

            $merchant = $response['merchant'];

            set_transient('vindi_merchant', $merchant, 1 * HOUR_IN_SECONDS);
        }

        return $merchant;
    }

    /**
     * Make an API request to retrieve informations about a charge.
     * @param int $id
     *
     * @return array|bool|mixed
     */
    public function get_charge($id)
    {
        $response = $this->request('charges/' . $id, 'GET');

        if (empty($response['charge']))
            return false;

        return $response['charge'];
    }

    /**
     * Check to see if Merchant Status is Trial or Sandbox Merchant.
     * @return boolean
     */
    public function is_merchant_status_trial_or_sandbox()
    {
        $merchant = $this->get_merchant();
        
        if ('trial' === $merchant['status'] || 'yes' === $this->sandbox)
            return true;
        
        return false;
    }

    /**
     * Verify API key authorization and clear
     * all transient data if access was denied
     *@param $api_key string
     *@return mixed|boolean|string
     */
    public function test_api_key($api_key)
    {
        delete_transient('vindi_merchant');

        $url         = $this->base_path() . 'merchant';
        $method      = 'GET';
		$request_id  = rand();
        $data_to_log = 'API Authorization Test';

		$this->logger->log(sprintf("[Request #%s]: Novo Request para a API.\n%s %s\n%s", $request_id, $method, $url, $data_to_log));

		$response = wp_remote_post( $url, [
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode($api_key . ':'),
				'Content-Type'  => 'application/json',
			    'User-Agent'    => sprintf('Vindi-WooCommerce-Subscriptions/%s; %s', Vindi_WooCommerce_Subscriptions::VERSION, get_bloginfo( 'url' )),
			],
			'method'    => $method,
			'timeout'   => 60,
			'sslverify' => true,
		] );

		if (is_wp_error($response)) {
			$this->logger->log(sprintf("[Request #%s]: Erro ao fazer request! %s", $request_id, print_r($response, true)));

			return false;
		}

		$status = $response['response']['code'] . ' ' . $response['response']['message'];
		$this->logger->log(sprintf("[Request #%s]: Nova Resposta da API.\n%s\n%s", $request_id, $status, print_r($response['body'], true)));

		$response_body = wp_remote_retrieve_body($response);

		if (!$response_body) {
			$this->logger->log(sprintf('[Request #%s]: Erro ao recuperar corpo do request! %s', $request_id, print_r($response, true)));

			return false;
		}

		$response_body_array = json_decode($response_body, true);

        if (isset($response_body_array['errors']) && !empty($response_body_array['errors'])) {
			foreach ($response_body_array['errors'] as $error) {
				if('unauthorized' == $error['id'] AND 'authorization' == $error['parameter']) {
                    delete_transient('vindi_plans');
                    delete_transient('vindi_payment_methods');
                    return $error['id'];
                }
			}
		}

		return true;
    }

    public function update_user_billing_informations($code, $informations)
    {
        $user_id = $this->find_customer_by_code($code);

        if(empty($user_id)) {
            return false;
        }

        $data = [
            'name'          => $informations['first_name'] . " " . $informations['last_name'],
            'registry_code' => empty($informations['cpf']) ? $informations['cnpj'] : $informations['cpf'],
            'email'         => $informations['email'],
            'address' => [
              'street'       => $informations['address_1'],
              'number'       => $informations['number'],
              'zipcode'      => $informations['postcode'],
              'neighborhood' => $informations['neighborhood'],
              'city'         => $informations['city'],
              'state'        => $informations['state'],
              'country'      => $informations['country']
            ]
        ];

        return $this->request("customers/{$user_id}", 'PUT', $data);
    }
}
