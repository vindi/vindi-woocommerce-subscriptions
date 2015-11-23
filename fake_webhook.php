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

print_r(post('{"event":{"type":"period_created","created_at":"2015-11-23T18:41:12.506-02:00","data":{"period":{"id":256269,"billing_at":"2015-11-23T00:00:00.000-02:00","cycle":1,"start_at":"2015-11-23T00:00:00.000-02:00","end_at":"2015-12-22T23:59:59.000-02:00","duration":2591999,"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"},"subscription":{"id":121576,"code":"122","plan":{"id":3614,"name":"Plano de 1 ano","code":""},"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"}},"usages":[{"id":332961,"quantity":1,"description":null,"created_at":"2015-11-23T18:41:12.000-02:00","product_item":{"id":157972,"product":{"id":6389,"name":"Mensalidade","code":""}},"bill":{"id":359427,"code":null}}],"created_at":"2015-11-23T18:41:12.000-02:00","updated_at":"2015-11-23T18:41:12.000-02:00"}}}}'));
