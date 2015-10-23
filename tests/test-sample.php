<?php

class Vindi_WooCommerce_Subscriptions extends WP_UnitTestCase
{

    private $plugin;

    public function setUp()
    {
        parent::setUp();
        $this->plugin = $GLOBALS['vindi-woocommerce-subscriptions'];
    }

    public function testPluginInitialization()
    {
        $this->assertFalse(null == $this->plugin);
    }
}
