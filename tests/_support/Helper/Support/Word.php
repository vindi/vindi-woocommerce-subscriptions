<?php

namespace Helper\Support;


class Word
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