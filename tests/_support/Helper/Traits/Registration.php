<?php

namespace Helper\Traits;


trait Registration
{

    /**
     * @When /^Eu vou para página de administração do WooCommerce na Tab "([^"]*)"$/
     */
    public function iGoToTheWoocommerceAdministrationPageOnTab($tab)
    {
        $tabText = '';
        if ($tab) {
            $tabs =
                [
                    'Geral' => 'general',
                    'Produtos' => 'products',
                    'Entrega' => 'shipping',
                    'Pagamentos' => 'checkout',
                    'Contas e privacidade' => 'account',
                    'E-mail' => 'email',
                    'Avançado' => 'advanced',
                    'Vindi' => 'settings_vindi',
                ];
            $tabText = "&tab={$tabs[$tab]}";
        }
        $this->amOnPage("wp-admin/admin.php?page=wc-settings$tabText");
    }


    /**
     * @When /^Eu clico no label "([^"]*)"$/
     * @param $labelText
     */
    public function iClickOnLabel($labelText)
    {
        $this->click("//label[text()=' $labelText']");
    }


    /**
     * @When /^Eu escrevo "([^"]*)" no campo do label "([^"]*)"$/
     * @param $text
     * @param $labelText
     */
    public function iTypeTextOnTheFieldOFLabel($text, $labelText)
    {
        if (array_key_exists($text, $_ENV)) {
            $text = $_ENV[$text];
        }
        $id = $this->grabAttributeFrom("//label[contains(text(),'$labelText')]", 'for');
        $this->fillField("#$id", $text);
    }

    /**
     * @When /^Eu clico no texto "([^"]*)"$/
     */
    public function iClickOnText($text)
    {
        $this->click("//*[contains(text(),'$text')]");
    }

    /**
     * @When /^Eu recarrego a página$/
     */
    public function refreshPage()
    {
        $this->reloadPage();
    }

    /**
     * @Then /^Eu vejo o parágrafo com texto "([^"]*)"$/
     */
    public function iSeeTextInParagraph($text)
    {
        $this->see($text, 'p');
    }

    /**
     * @When /^Eu clico na configuração de pagamento "([^"]*)"$/
     */
    public function iClickOnCheckConfiguration($payment)
    {
        $payments =
            [
                'Transferência bancária direta' => 'bacs',
                'Cheque' => 'cheque',
                'Pagamento na entrega' => 'cod',
                'PayPal' => 'paypal',
                'Vindi - Boleto Bancário' => 'vindi-bank-slip',
                'Vindi - Cartão de Crédito' => 'vindi-wc-creditcard',
            ];
        $this->click("tr[data-gateway_id={$payments[$payment]}] td[class=action] a");
    }


}