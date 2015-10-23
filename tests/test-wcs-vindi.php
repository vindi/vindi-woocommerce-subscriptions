<?php

class WCS_Vindi_Test extends WP_UnitTestCase
{

    private $plugin;

    public function setUp()
    {
        parent::setUp();
        if(array_key_exists(WSC_Vindi::IDENTIFIER, $GLOBALS))
            $this->plugin = $GLOBALS[WSC_Vindi::IDENTIFIER];
    }

    public function testPluginInitialization()
    {
        $this->assertFalse(null == $this->plugin);
    }
}
