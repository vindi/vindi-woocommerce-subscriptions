<?php

class Vindi_Webhook_Handler
{
    /**
	 * @var Vindi_Settings
	 */
	private $container;

    /**
	 * @param Vindi_Settings $container
	 */
	public function __construct(Vindi_Settings $container)
    {
        $this->container = $container;
	}

	/**
	 * Handle incoming webhook.
	 */
	public function handle()
    {
        $token    = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        $raw_body = file_get_contents('php://input');
        $body     = json_decode($raw_body);

        if(!$this->validate_access_token($token))
            die('invalid access token');

        $this->container->logger->log('Novo Webhook chamado: ' . $raw_body);

        try {
             $this->process_event($body);
        } catch (Exception $e) {
            $this->container->logger->log($e->getMessage());

            if(2 === $e->getCode()) {
                http_response_code(422);
                die($e->getMessage());
            }
        }
	}

    /**
     * @param string $token
     **/
    private function validate_access_token($token)
    {
        return $token === $this->container->get_token();
    }

    /**
     * Read json entity received and proccess the right event
     * @param string $body
     **/
    private function process_event($body)
    {
        if(null == $body || empty($body->event))
            throw new Exception('Falha ao interpretar JSON do webhook: Evento do Webhook não encontrado!');

		$type = $body->event->type;
		$data = $body->event->data;

        if(method_exists($this, $type)) {
            $this->container->logger->log('Novo Evento processado: ' . $type);
            return $this->{$type}($data);
        }

        $this->container->logger->log('Evento do webhook ignorado pelo plugin: ' . $type);
    }

    /**
     * Process test event from webhook
     * @param $data array
     */
    private function test($data)
    {
        $this->container->logger->log('Evento de teste do webhook.');
    }

    // /**
    //  * Process subscription_created event from webhook
    //  * @param $data array
    //  **/
    // private function subscription_created($data)
    // {
    //     $subscription = $this->find_subscription_by_id($data->bill->subscription->code);
    //
    //     $this->container->logger->log('Nova assinatura criada: #' . $subscription->id);
    //     $subscription->add_order_note(__('O pedido foi recebido com sucesso pela Vindi e está sendo processado.', VINDI_IDENTIFIER));
    // 	$this->container->logger->log('O Pedido #' . $subscription->id . ' foi recebido com sucesso pela Vindi.');
    // }

    /**
     * Process period_created event from webhook
     * @param $data array
     **/
    private function period_created($data)
    {
        $cycle           = $data->period->cycle;
        $subscription    = $this->find_subscription_by_id($data->period->subscription->code);

        if($this->find_order_by_cycle($subscription->id, $cycle))
            throw new Exception('Já existe o ciclo $cycle para a assinatura ' . $data->period->subscription->id . ' pedido ' . $subscription->id);

        WC_Subscriptions_Manager::prepare_renewal($subscription->id);
        $order_id = $subscription->get_last_order();
        $order    = $this->find_order_by_id($order_id);
        add_post_meta($order->id, 'vindi_wc_cycle', $cycle);
        $this->container->logger->log('Novo Período criado: Pedido #'.$order->id);
    }

    /**
     * Process bill_paid event from webhook
     * @param $data array
     **/
    private function bill_created($data)
    {
        if($data->bill->subscription) {
            $wc_subscription_id    = $data->bill->subscription->code;
            $vindi_subscription_id = $data->bill->subscription->id;
            $cycle                 = $data->bill->period->cycle;
            $order                 = $this->find_order_by_cycle($vindi_subscription_id, $cycle);

            if(!$order)
                throw new Exception('Não existe o ciclo $cycle para a assinatura ' . $data->period->subscription->id . ' pedido ' . $wc_subscription_id, 2);

            add_post_meta($order->id, 'vindi_wc_bill_id', $data->bill->id);
        }
    }

    /**
     * Process bill_paid event from webhook
     * @param $data array
     **/
    private function bill_paid($data)
    {
        if(empty($data->bill->subscription)) {
            $order = $this->find_order_by_id($data->bill->code);
        } else {
            $wc_subscription_id    = $data->bill->subscription->code;
            $vindi_subscription_id = $data->bill->subscription->id;
            $cycle                 = $data->bill->period->cycle;
            $subscription          = $this->find_subscription_by_id($wc_subscription_id);
            $order                 = $this->find_order_by_cycle($vindi_subscription_id, $cycle);
        }
        
        $order->payment_complete();
        $order->add_order_note('Nova confirmação de pagamento!');
    }

    /**
     * Process charge_rejected event from webhook
     * @param $data array
     **/
    private function charge_rejected($data)
    {
        $order = $this->find_order_by_bill_id($data->charge->bill->id);
        
        if($order->get_status() == 'pending'){
            $order->update_status('failed', 'Pagamento rejeitado!');
        }else{
            throw new Exception('A fatura #' . $data->charge->bill->id . ' não está mais pendente!');
        }
    }

    /**
     * Process subscription_canceled event from webhook
     * @param $data array
     **/
    private function subscription_canceled($data)
    {
        $subscription_id = $data->subscription->code;
        $subscription    = $this->find_subscription_by_id($subscription_id);
        $subscription->cancel_order();
    }

    /**
     * find a subscription by id
     * @param int id
     * @return WC_Subscription
     **/
    private function find_subscription_by_id($id)
    {
        $subscription = wcs_get_subscription($id);

        if(empty($subscription))
            throw new Exception('Assinatura #' . $id . ' não encontrada!', 2);

        return $subscription;
    }

    /**
     * find a order by id
     * @param int id
     *
     * @return WC_Order
     **/
    private function find_order_by_id($id)
    {
        $order = wc_get_order($id);

        if(empty($order))
            throw new Exception('Pedido #' . $id . ' não encontrado!', 2);

        return $order;
    }

    /**
	 * find orders by bill_id meta
	 *
	 * @param int $bill_id
	 *
	 * @return WC_Order
	 */
	private function find_order_by_bill_id($bill_id)
    {
        $args = array(
			'post_type'   => 'shop_order',
			'meta_key'    => 'vindi_wc_bill_id',
			'meta_value'  => $bill_id,
			'post_status' => 'any',
		);

        $query = new WP_Query($args);

        if(false === $query->have_posts())
            throw new Exception('Pedido com bill_id #' . $bill_id . ' não encontrado!', 2);

        return wc_get_order($query->post->ID);
	}

    /**
	 * Query orders containing cycle meta
	 *
	 * @param int $subscription_id
	 * @param int $cycle
	 *
	 * @return WC_Order
	 */
	private function find_order_by_cycle($subscription_id, $cycle)
    {
		$args = array(
			'post_type'   => 'shop_order',
            'meta_query' => array(
                array(
        			'key'    => 'vindi_wc_cycle',
        			'value'  => $cycle,
                ),
                array(
        			'key'    => 'vindi_wc_subscription_id',
        			'value'  => $subscription_id,
                )
            ),
			'post_status' => 'any',
		);

        $query = new WP_Query($args);

        if(false === $query->have_posts())
            throw new Exception('Pedido da assinatura #' . $subscription_id . ' para o ciclo #' . $cycle . ' não encontrada!', 2);

        return wc_get_order($query->post->ID);
	}
}
