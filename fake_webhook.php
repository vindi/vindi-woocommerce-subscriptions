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

print_r(post('{"event":{"type":"bill_paid","created_at":"2015-11-23T18:41:12.397-02:00","data":{"bill":{"id":359428,"code":null,"amount":"100.0","installments":1,"status":"paid","seen_at":null,"billing_at":null,"due_at":null,"url":"https://app.vindi.com.br/customer/bills/359427?token=0611215b-3992-4709-8b8a-a2c92ccfc369","created_at":"2015-11-23T18:41:12.000-02:00","updated_at":"2015-11-23T18:41:12.366-02:00","bill_items":[{"id":464055,"amount":"100.0","quantity":1,"pricing_range_id":null,"description":null,"pricing_schema":{"id":59112,"short_format":"R$ 100,00","price":"100.0","minimum_price":null,"schema_type":"flat","pricing_ranges":[],"created_at":"2015-11-23T18:41:11.000-02:00"},"product":{"id":6389,"name":"Mensalidade","code":""},"product_item":{"id":157972,"product":{"id":6389,"name":"Mensalidade","code":""}},"discount":null}],"charges":[{"id":315312,"amount":"100.0","status":"paid","due_at":"2015-11-23T23:59:59.000-02:00","paid_at":"2015-11-23T18:41:12.000-02:00","installments":1,"attempt_count":1,"next_attempt":null,"print_url":null,"created_at":"2015-11-23T18:41:12.000-02:00","updated_at":"2015-11-23T18:41:12.000-02:00","last_transaction":{"id":463425,"transaction_type":"charge","status":"success","amount":"100.0","installments":1,"gateway_message":"Transacao aprovada em modo teste","gateway_response_code":"","gateway_authorization":"0C887575D1B541097BF2C9F2FBDA41F2","gateway_transaction_id":"9b4d7999-aa6f-4387-bbd0-7b17d5f2008d","fraud_detector_score":null,"fraud_detector_status":null,"fraud_detector_id":null,"created_at":"2015-11-23T18:41:12.000-02:00","payment_profile":{"id":180388,"holder_name":"ERICO PEDROSO","registry_code":null,"bank_branch":null,"bank_account":null,"card_expiration":"2019-05-31T23:59:59.000-03:00","card_number_first_six":"555555","card_number_last_four":"5557","token":"099cbec3-ce11-4f67-87ba-8df8352f4cd6","created_at":"2015-11-23T18:41:10.000-02:00","payment_company":{"id":1,"name":"MasterCard","code":"mastercard"}}},"payment_method":{"id":3501,"public_name":"Cartão de crédito","name":"Cartão de crédito","code":"credit_card","type":"PaymentMethod::CreditCard"}}],"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"},"period":{"id":256269,"billing_at":"2015-11-23T00:00:00.000-02:00","cycle":1,"start_at":"2015-11-23T00:00:00.000-02:00","end_at":"2015-12-22T23:59:59.000-02:00","duration":2591999},"subscription":{"id":121576,"code":"122","plan":{"id":3614,"name":"Plano de 1 ano","code":""},"customer":{"id":175835,"name":"Érico Pedroso","email":"erico.pedroso@vindi.com.br","code":"wc-1-1447259503"}},"metadata":{}}}}}'));
