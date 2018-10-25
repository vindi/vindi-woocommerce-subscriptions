<?php

namespace Page;

class CustomerPopulate
{
    public static
        $URL = '',
        $form = 'form[name="checkout"]',
        $name = 'billing_first_name',
        $surname = 'billing_last_name',
        $personType = 'billing_persontype',
        $cpf = 'billing_cpf',
        $country = 'billing_country',
        $cep = 'billing_postcode',
        $address = 'billing_address_1',
        $number = 'billing_number',
        $complement = 'billing_address_2',
        $block = 'billing_neighborhood',
        $city = 'billing_city',
        $state = 'billing_state',
        $landLine = 'billing_phone',
        $mobile = 'billing_cellphone',
        $email = 'billing_email',
        $submit = '#place_order';

//        $holder = 'vindi_cc_fullname',
//        $brand = 'vindi_cc_paymentcompany',
//        $cardNumber = 'vindi_cc_number',
//        $month = 'vindi_cc_monthexpiry',
//        $year = 'vindi_cc_yearexpiry',
//        $cvv = 'vindi_cc_cvc',
//        $installment = 'vindi_cc_installments';

    protected
        $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }


    public static function route($param)
    {
        return static::$URL . $param;
    }

    public function populate()
    {
        $I = $this->tester;
        $I->fillField(self::$name, 'Vindi');
        $I->fillField(self::$surname, 'Recorrência');
        $I->selectOption(self::$personType, 'Pessoa Física');
        $I->fillField(self::$cpf, '07654509847');
        $I->selectOption(self::$country, 'Brasil');
        $I->fillField(self::$cep, '04031-050');
        $I->fillField(self::$address, 'Rua Sena Madureira');
        $I->fillField(self::$number, '163');
        $I->fillField(self::$complement, 'prédio');
        $I->fillField(self::$block, 'Vila Mariana');
        $I->fillField(self::$city, 'São Paulo');
        $I->selectOption(self::$state, 'São Paulo');
        $I->fillField(self::$landLine, '1159047380');
        $I->fillField(self::$mobile, '1159047380');
        $I->fillField(self::$email, 'comunidade@vindi.com.br');


//        $I->fillField(self::$holder, 'Vindi Recorrência');
//        $I->selectOption(self::$brand, 'MasterCard');
//        $I->fillField(self::$cardNumber, '5555555555555557');
//        $I->selectOption(self::$month, '10 - outubro');
//        $I->selectOption(self::$year, '2019');
//        $I->fillField(self::$cvv, 1234);
//        $I->selectOption(self::$installment, '2x de R$12,50');
//        $I->click(self::$submit);

//        return $this;
    }


}
