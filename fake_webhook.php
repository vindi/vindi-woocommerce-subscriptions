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

print_r(post('{"event":{"type":"subscription_created","created_at":"2015-11-23T10:52:41.006-02:00","data":{"subscription":{"id":121104,"status":"active","start_at":"2015-11-23T00:00:00.000-02:00","next_billing_at":"2015-12-23T00:00:00.000-02:00","code":"91","cancel_at":null,"interval":"months","interval_count":1,"billing_trigger_type":"beginning_of_period","billing_trigger_day":0,"billing_cycles":12,"installments":1,"created_at":"2015-11-23T10:52:40.605-02:00","updated_at":"2015-11-23T10:52:40.605-02:00","customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"},"plan":{"id":3614,"name":"Plano de 1 ano","code":""},"product_items":[{"id":157378,"status":"active","cycles":null,"quantity":1,"created_at":"2015-11-23T10:52:40.000-02:00","updated_at":"2015-11-23T10:52:40.000-02:00","product":{"id":6389,"name":"Mensalidade","code":""},"pricing_schema":{"id":58046,"short_format":"R$ 100,00","price":"100.0","minimum_price":null,"schema_type":"flat","pricing_ranges":[],"created_at":"2015-11-23T10:52:40.000-02:00"},"discounts":[]}],"payment_method":{"id":3502,"public_name":"Boleto bancário","name":"Boleto bancário","code":"bank_slip","type":"PaymentMethod::BankSlip"},"current_period":{"id":255785,"billing_at":"2015-11-23T00:00:00.000-02:00","cycle":1,"start_at":"2015-11-23T00:00:00.000-02:00","end_at":"2015-12-22T23:59:59.000-02:00","duration":2591999},"metadata":{}}}}}));
