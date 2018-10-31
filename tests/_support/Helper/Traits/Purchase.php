<?php

namespace Helper\Traits;

use Helper\Support\Gateway;
use Page\CreditCardConfiguration;
use Page\CreditCardPopulate;
use Page\CustomerPopulate;

trait Purchase
{
    private static $code;

    /**
     * @When /^Eu configuro pagamento no cartão de crédito$/
     */
    public function iSetUpCreditCardPayment()
    {
        (new CreditCardConfiguration($this))->register();
    }

    /**
     * @Given /^Eu estou na loja$/
     */
    public function iAmOnStore()
    {
        $this->amOnPage('/');
    }

    /**
     * @When /^Eu preencho dados do cliente$/
     */
    public function iPopulateCustomer()
    {
        (new CustomerPopulate($this))->populate();
    }

    /**
     * @When /^Eu preencho dados do cartão de crédito$/
     */
    public function iPopulateCreditCard()
    {
        (new CreditCardPopulate($this))->populate();
    }

    /**
     * @When /^Eu clico em Finalizar compra$/
     */
    public function iClickOnFinishPurchase()
    {
        $this->click(CustomerPopulate::$submit);
    }

    /**
     * @When /^Eu zero o code$/
     */
    public function iResetCode()
    {
        self::$code = (new Gateway)->resetCode();
    }

    /**
     * @Then /^Eu confirmo a compra no gateway da Vindi$/
     */
    public function iConfirmPurchase()
    {
        $bill = (new Gateway())->billByCode(self::$code);
        $this->seePostMetaInDatabase([
            'post_id' => self::$code,
            'meta_key' => 'vindi_wc_bill_id',
            'meta_value' => $bill['id'],
        ]);
        $this->seePostMetaInDatabase([
            'post_id' => self::$code,
            'meta_key' => '_order_total',
            'meta_value' => (int)$bill['amount'],
        ]);
    }
}