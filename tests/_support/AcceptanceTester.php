<?php


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
    use _generated\AcceptanceTesterActions;

    /**
     * @Given /^Eu estou logado como administrador$/
     */
    public function iAmLoggedInAsAnAdministrator()
    {
        $this->loginAsAdmin();
    }

    /**
     * @When /^Eu vou para página de administração do plugin$/
     */
    public function iGoToThePluginsAdministrationPage()
    {
        $this->amOnPluginsPage();
    }

    /**
     * @Then /^Eu deveria ver o plugin "([^"]*)"$/
     * @param $pluginName
     */
    public function iShouldSeeThePlugin($pluginName)
    {
        $this->seeElement('#the-list  tr[data-slug="' . $this->buildPluginSlug($pluginName) . '"]');
    }


    /**
     * @When /^Eu ativo o plugin "([^"]*)"$/
     * @param $pluginName
     */
    public function iActivateThePlugin($pluginName)
    {
        $this->activatePlugin($this->buildPluginSlug($pluginName));
    }

    /**
     * @Then /^Eu deveria ver o plugin "([^"]*)" ativado$/
     * @param $pluginName
     */
    public function iShouldSeeThePluginActivated($pluginName)
    {
        $this->seePluginActivated($this->buildPluginSlug($pluginName));
    }

    /**
     * @Given /^O plugin "([^"]*)" está ativado$/
     * @param $pluginName
     */
    public function thePluginIsActivated($pluginName)
    {
        $activePlugins = $this->grabOptionFromDatabase('active_plugins');
        $activePlugins = empty($activePlugins) ? [] : $activePlugins;
        $isFullSlug = preg_match('/^.*\\.php$/', $pluginName);
        $pluginFullSlug = [$pluginName];

        if (!$isFullSlug) {
            $pluginSlug = $this->buildPluginSlug($pluginName);
            $pluginFullSlug = ["{$pluginSlug}/{$pluginSlug}.php", "{$pluginSlug}/plugin.php"];
        }

        if (empty($activePlugins) || !count(array_intersect($pluginFullSlug, $activePlugins))) {
            $activePlugins = array_merge($activePlugins, $pluginFullSlug);
            $this->haveOptionInDatabase('active_plugins', $activePlugins);
        }
    }

    /**
     * @When /^Eu desativo o plugin "([^"]*)"$/
     * @param $pluginName
     */
    public function iDeactivateThePlugin($pluginName)
    {
        $this->deactivatePlugin($this->buildPluginSlug($pluginName));
        $this->reloadPage();

    }

    /**
     * @Then /^Eu deveria ver o plugin "([^"]*)" desativado$/
     * @param $pluginName
     */
    public function iShouldSeeThePluginDeactivated($pluginName)
    {
        $this->seeElement('#the-list  tr[data-slug="' . $this->buildPluginSlug($pluginName) . '"] td div span[class="activate"]');
        $this->reloadPage();
    }

    /**
     * @When /^Eu vou para página de administração do WooCommerce$/
     */
    public function iGoToTheWoocommerceAdministrationPageOnTabVindi()
    {
        $this->amOnPage('wp-admin/admin.php?page=wc-settings&tab=settings_vindi');
    }

    /**
     * @Then /^Eu vejo a tab "([^"]*)"$/
     * @param $tab
     */
    public function iSeeTab($tab)
    {
        $this->seeElement("//a[text()='$tab']");
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
     * @When /^Eu escrevo o "([^"]*)" no campo do label "([^"]*)"$/
     * @param $text
     * @param $labelText
     */
    public function iTypeTextOnTheFieldOFLabel($text, $labelText)
    {
        if (array_key_exists($text, $_ENV)) {
            $text = $_ENV[$text];
        }
        $id = $this->grabAttributeFrom("//label[text()='$labelText ']", 'for');
        $this->fillField("#$id", $text);
    }

    /**
     * @When /^Eu clico em "([^"]*)"$/
     */
    public function iClickOn($text)
    {
        $this->click("//button[text()='$text']");
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
    public function iSeeText($text)
    {
        $this->see( $text, 'p');
    }


}
