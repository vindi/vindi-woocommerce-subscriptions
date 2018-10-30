<?php

namespace Helper\Traits;

use Page\CreditCardConfiguration;
use Page\CreditCardPopulate;
use Page\CustomerPopulate;

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
}