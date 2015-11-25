<?php

function post($data)
{
    $data_string = $data;

    $ch = curl_init('http://127.0.0.1/wp/wordpress/index.php/wc-api/vindi_webhook?token=b71c253f28f4d5a151074fd783fb941f');
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

print_r(post('{"event":{"type":"charge_rejected","created_at":"2015-11-25T20:53:15.132-02:00","data":{"charge":{"id":322448,"amount":"15.19","status":"pending","due_at":"2015-11-25T23:59:59.000-02:00","paid_at":null,"installments":1,"attempt_count":1,"next_attempt":"2015-11-28T00:00:00.000-02:00","print_url":null,"created_at":"2015-11-25T20:53:14.000-02:00","updated_at":"2015-11-25T20:53:15.000-02:00","last_transaction":{"id":477828,"transaction_type":"charge","status":"rejected","amount":"15.19","installments":1,"gateway_message":"Transacao rejeitada em modo teste","gateway_response_code":"51","gateway_authorization":"B333CE749E73746623EEF74EEBCB2372","gateway_transaction_id":"4a56878c-2905-4ba9-961a-e711ae709dd9","gateway_response_fields":null,"fraud_detector_score":null,"fraud_detector_status":null,"fraud_detector_id":null,"created_at":"2015-11-25T20:53:14.000-02:00","payment_profile":{"id":182840,"holder_name":"TESTE","registry_code":null,"bank_branch":null,"bank_account":null,"card_expiration":"2025-03-31T23:59:59.000-03:00","card_number_first_six":"541295","card_number_last_four":"2630","token":"bedb4593-44ec-4eb7-b9de-376304cf3a2c","created_at":"2015-11-25T20:53:13.000-02:00","payment_company":{"id":1,"name":"MasterCard","code":"mastercard"}}},"payment_method":{"id":25,"public_name":"Cartão de crédito","name":"Cartão de crédito","code":"credit_card","type":"PaymentMethod::CreditCard"},"bill":{"id":370013,"code":null},"customer":{"id":203570,"name":"Lyon Oliveira","email":"lyoncesar-buyer@gmail.com","code":"wc-1-1448484448"}}}}}'));
