<?php

namespace Helper\Traits;

use Page\CreditCardConfiguration;
use Page\CustomerPopulate;
use Page\CreditCardPopulate;

trait Purchase
{
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
     * @When /^Eu registro uma compra$/
     */
    public function iPopulateCustomer()
    {
        (new CustomerPopulate($this))->populate();
        (new CreditCardPopulate($this))->populate();
        $this->click(CustomerPopulate::$submit);
    }

}