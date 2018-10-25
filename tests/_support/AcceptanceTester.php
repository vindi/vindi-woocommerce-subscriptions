<?php

use Page\CreditCardConfiguration;
use Page\CustomerPopulate;
use Page\CreditCardPopulate;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use
        _generated\AcceptanceTesterActions,
        Helper\Traits\Activation,
        Helper\Traits\Registration,
        Helper\Traits\Purchase;

    /**
     * @When /^Eu crio um dump$/
     */
    public function iCreateDump()
    {
        $this->cli('db export ./tests/_data/dump.sql --dbuser=root --dbpass=123 --add-drop-table --allow-root');
    }

    /**
     * @When /^Eu clico no título "([^"]*)"$/
     */
    public function iClickOnTitle($title)
    {
        $this->click("*[title='$title']");
    }

    /**
     * @When /^Eu clico no texto que contenha "([^"]*)"$/
     */
    public function iClickOnTextThatContains($text)
    {
        $this->click("//*[contains(text(),'$text')]");
    }

    /**
     * @When /^Eu seleciono do label "([^"]*)" a opção "([^"]*)"$/
     */
    public function iSelectFromLabelAnOption($labelText, $option)
    {
        $id = $this->grabAttributeFrom("//label[contains(text(),'$labelText')]", 'for');
        $this->selectOption("select[id='$id']", "$option");
    }

    /**
     * @Then /^Eu vejo "([^"]*)"$/
     */
    public function iSee($text)
    {
        $this->see($text);
    }

}
