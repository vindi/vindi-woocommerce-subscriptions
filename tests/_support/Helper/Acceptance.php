<?php

namespace Helper;


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
