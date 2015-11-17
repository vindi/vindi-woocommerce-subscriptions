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
        $data     = json_decode(file_get_contents('php://input'));
        $response = '';

        if(!$this->validate_access_token($token))
            die('invalid access token');

        $this->handle_event($data);
	}

    /**
     * @param string $received_token
     **/
    public function validate_access_token($received_token)
    {
        return $received_token === $this->container->get_token();
    }

    /**
     * Read json entity received and proccess the right event
     * @param string $data
     **/
    public function handle_event($data)
    {
        if(null == $data || empty($data['event'])) {
            http_response_code(429);
            die('Invalid JSON');
        }
    }
}
