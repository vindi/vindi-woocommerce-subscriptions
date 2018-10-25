<?php

namespace Page;

class CreditCardPopulate
{
    public static
        $URL = '',
        $holder = 'vindi_cc_fullname',
        $brand = 'vindi_cc_paymentcompany',
        $cardNumber = 'vindi_cc_number',
        $month = 'vindi_cc_monthexpiry',
        $year = 'vindi_cc_yearexpiry',
        $cvv = 'vindi_cc_cvc',
        $installment = 'vindi_cc_installments',
        $submit = '#place_order';

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
        $I->fillField(self::$holder, 'Vindi Recorrência');
        $I->selectOption(self::$brand, 'MasterCard');
        $I->fillField(self::$cardNumber, '5555555555555557');
        $I->selectOption(self::$month, '10 - outubro');
        $I->selectOption(self::$year, '2019');
        $I->fillField(self::$cvv, 123);
        $I->selectOption(self::$installment, '2x de R$12,50');
    }
}
