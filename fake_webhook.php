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

print_r(post('{"event":{"type":"bill_paid","created_at":"2015-11-25T19:34:52.411-02:00","data":{"bill":{"id":369870,"code":null,"amount":"140.0","installments":1,"status":"paid","seen_at":null,"billing_at":null,"due_at":null,"url":"https://app.vindi.com.br/customer/bills/369870?token=4b52512c-44c7-470b-9aa0-ef859facd416","created_at":"2015-11-25T19:31:27.000-02:00","updated_at":"2015-11-25T19:34:52.362-02:00","bill_items":[{"id":476487,"amount":"100.0","quantity":1,"pricing_range_id":null,"description":null,"pricing_schema":{"id":63161,"short_format":"R$ 100,00","price":"100.0","minimum_price":null,"schema_type":"flat","pricing_ranges":[],"created_at":"2015-11-25T19:31:27.000-02:00"},"product":{"id":9660,"name":"Plano de 1 ano","code":"82"},"product_item":{"id":160491,"product":{"id":9660,"name":"Plano de 1 ano","code":"82"}},"discount":null},{"id":476488,"amount":"40.0","quantity":1,"pricing_range_id":null,"description":null,"pricing_schema":{"id":63162,"short_format":"R$ 40,00","price":"40.0","minimum_price":null,"schema_type":"flat","pricing_ranges":[],"created_at":"2015-11-25T19:31:27.000-02:00"},"product":{"id":9671,"name":"Frete (Flat Rate)","code":"flat-rate"},"product_item":{"id":160492,"product":{"id":9671,"name":"Frete (Flat Rate)","code":"flat-rate"}},"discount":null}],"charges":[{"id":322318,"amount":"140.0","status":"paid","due_at":"2015-11-30T23:59:59.000-02:00","paid_at":"2015-11-25T00:00:00.000-02:00","installments":1,"attempt_count":1,"next_attempt":null,"print_url":null,"created_at":"2015-11-25T19:31:28.000-02:00","updated_at":"2015-11-25T19:34:52.000-02:00","last_transaction":{"id":477702,"transaction_type":"charge","status":"success","amount":"140.0","installments":null,"gateway_message":null,"gateway_response_code":null,"gateway_authorization":"","gateway_transaction_id":null,"gateway_response_fields":null,"fraud_detector_score":null,"fraud_detector_status":null,"fraud_detector_id":null,"created_at":"2015-11-25T19:34:52.000-02:00","payment_profile":null},"payment_method":{"id":3503,"public_name":"Dinheiro","name":"Dinheiro","code":"cash","type":"PaymentMethod::Cash"}}],"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"},"period":{"id":260100,"billing_at":"2015-11-25T00:00:00.000-02:00","cycle":1,"start_at":"2015-11-25T00:00:00.000-02:00","end_at":"2015-12-24T23:59:59.000-02:00","duration":2591999},"subscription":{"id":123373,"code":"176","plan":{"id":3614,"name":"Plano de 1 ano","code":""},"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"}},"metadata":{}}}}}'));
