<?php

function post($data)
{
    $data_string = $data;

    $ch = curl_init('http://wordpress.vindi.dev/index.php/wc-api/vindi_webhook?token=bea18210ebce17cb5f3ee30a5ed8ef37');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    return $result;
}

print_r(post('{"event":{"type":"test","created_at":"2015-11-20T14:31:47.436-02:00","data":{"quote":{"content":"High score: 2,814,794,693  Your score: 26"}}}}'));
