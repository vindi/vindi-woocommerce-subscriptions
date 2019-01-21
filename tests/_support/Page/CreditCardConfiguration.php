<?php

namespace Page;

class CreditCardConfiguration
{
    public static
        $URL = '',
        $enable = 'woocommerce_vindi-wc-creditcard_enabled',
        $title = 'woocommerce_vindi-wc-creditcard_title',
        $verify = 'woocommerce_vindi-wc-creditcard_verify_method',
        $minimumInstallment = 'woocommerce_vindi-wc-creditcard_smallest_installment',
        $maximumInstallment = 'woocommerce_vindi-wc-creditcard_installments',
        $save = 'save';

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

    public function register()
    {
        $I = $this->tester;
        $I->checkOption(self::$enable);
        $I->fillField(self::$title, 'Cartão de Crédito');
        $I->fillField(self::$minimumInstallment, '5');
        $I->selectOption(self::$maximumInstallment, '12x');
        $I->click(self::$save);
    }
}
