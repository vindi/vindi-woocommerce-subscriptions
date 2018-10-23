<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    /**
     * @param $pluginName
     *
     * @return string
     */
    public function buildPluginSlug($pluginName)
    {
        return implode('-', array_map('strtolower', preg_split('/[-_\\s]/', $pluginName))) . '-subscriptions';
    }

}
