<?php

class WCS_Vindi_Test extends WP_UnitTestCase
{

    private $plugin;

    public function setUp()
    {
        parent::setUp();
        if(array_key_exists(VINDI_IDENTIFIER, $GLOBALS))
            $this->plugin = $GLOBALS[VINDI_IDENTIFIER];
    }

    public function testPluginInitialization()
    {
        $this->assertInstanceOf('Vindi_WooCommerce_Subscriptions', $this->plugin);
    }
}
