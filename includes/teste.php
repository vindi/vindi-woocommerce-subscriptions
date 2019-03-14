<?php
$body = [
    'name' => 'Plano Golden',
    'interval' => 'months',
    'interval_count' => 1,
    'billing_trigger_type' => 'beginning_of_period',
    'billing_trigger_day' => 0,
    'installments' => 1
];
$plan = $api->create_plan($body);
$handler = fopen(plugin_dir_path(__FILE__) . 'teste.txt', "w+");
fwrite($handler, $plan['id']);
fclose($handler);
