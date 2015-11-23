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

            if(2 === $e->getCode())
                http_response_code(429);
        }
        exit;
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

    }

    /**
     * Process subscription_created event from webhook
     * @param $data array
     **/
    private function subscription_created($data)
    {
        print_r($data->subscription->code);
    }

    /**
     * Process bill_paid event from webhook
     * @param $data array
     **/
    private function bill_paid($data)
    {

    }

    /**
     * Process charge_rejected event from webhook
     * @param $data array
     **/
    private function charge_rejected($data)
    {

    }
}
