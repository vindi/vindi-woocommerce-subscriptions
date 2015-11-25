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

        $this->container->logger->log(sprintf('Novo Webhook chamado: %s', $raw_body));

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
            $this->container->logger->log(sprintf('Novo Evento processado: %s', $type));
            return $this->{$type}($data);
        }

        $this->container->logger->log(sprintf('Evento do webhook ignorado pelo plugin: %s', $type));
    }

    /**
     * Process test event from webhook
     * @param $data array
     */
    private function test($data)
    {
        $this->container->logger->log('Evento de teste do webhook.');
    }

    /**
     * Process subscription_created event from webhook
     * @param $data array
     **/
    private function subscription_created($data)
    {
        $subscription = $this->find_subscription($data->bill->subscription->code);

        $this->container->logger->log(sprintf('Nova assinatura criada: %d', $subscription->id));
        $subscription->add_order_note(__('O pedido foi recebido com sucesso pela Vindi e está sendo processado.', VINDI_IDENTIFIER));
    	$this->container->logger->log(sprintf( 'O pedido %d foi recebido com sucesso pela Vindi.', $subscription->id));
    }

    /**
     * Process period_created event from webhook
     * @param $data array
     **/
    private function period_created($data)
    {
        $cycle           = $data->period->cycle;
        $subscription    = $this->find_subscription($data->period->subscription->code);
        $total_orders    = count($subscription->get_related_orders());

        if($cycle <= $total_orders)
            throw new Exception("Não foi possível criar um novo pedido para o ciclo " . $cycle, 2);

        WC_Subscriptions_Manager::prepare_renewal($subscription->id);
    }

    /**
     * Process bill_paid event from webhook
     * @param $data array
     **/
    private function bill_created($data)
    {
        if(false === empty($data->bill->subscription)) {
            $subscription = $this->find_subscription($data->bill->subscription->code);
            $order_id     = $subscription->get_last_order();
            $order        = $this->find_order($order_id);
        } else {
            $order = $this->find_order($data->bill->code);
        }

        if(false !== $this->query_bill_id($data->bill->id))
            return ;

        if(get_post_meta($order->id, 'vindi_wc_bill_id'))
            throw new Exception(sprintf('Periodo ainda não criando para a fatura %d', $data->bill->id), 2);

        add_post_meta($order->id, 'vindi_wc_bill_id', $data->bill->id);
        $this->container->logger->log('Pedido atualizado com bill_id ' . $data->bill->id);
    }

    /**
     * Process bill_paid event from webhook
     * @param $data array
     **/
    private function bill_paid($data)
    {
        $order = $this->find_order_by_bill_id($data->bill->id);
        $order->payment_complete();
    }

    /**
     * Process subscription_canceled event from webhook
     * @param $data array
     **/
    private function subscription_canceled($data)
    {
        $subscription_id = $data->subscription->code;
        $subscription    = $this->find_subscription($subscription_id);
        $subscription->cancel_order();
    }

    /**
     * find a subscription by id
     * @param int id
     * @return WC_Subscription
     **/
    private function find_subscription($id)
    {
        $subscription = wcs_get_subscription($id);

        if(empty($subscription))
            throw new Exception(sprintf('Assinatura #%d não encontrada!', $id), 2);

        return $subscription;
    }

    /**
     * find a order by id
     * @param int id
     *
     * @return WC_Order
     **/
    private function find_order($id)
    {
        $order = wc_get_order($id);

        if(empty($order))
            throw new Exception(sprintf('Pedido #%d não encontrado!', $id), 2);

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
		$query = $this->query_bill_id($bill_id);

        if(false === $query->have_posts())
            throw new Exception(sprintf('Pedido com bill_id %d não encontrado!', $bill_id), 2);

        return wc_get_order($query->post->ID);
	}

    /**
	 * Query orders containing bill_id meta
	 *
	 * @param int $bill_id
	 *
	 * @return WC_Order
	 */
	private function query_bill_id($bill_id)
    {
		$args = array(
			'post_type'   => 'shop_order',
			'meta_key'    => 'vindi_wc_bill_id',
			'meta_value'  => $bill_id,
			'post_status' => 'any',
		);

        $query = new WP_Query($args);

        if($query->have_posts())
            return $query;

        return false;
	}
}
