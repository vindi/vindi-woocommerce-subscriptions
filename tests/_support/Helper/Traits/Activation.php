<?php

namespace Helper\Traits;


use Helper\Support\Word;

trait Activation
{
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
        $this->seeElement('#the-list  tr[data-slug="' . (new Word)->buildPluginSlug($pluginName) . '"]');
    }

    /**
     * @When /^Eu ativo o plugin "([^"]*)"$/
     * @param $pluginName
     */
    public function iActivateThePlugin($pluginName)
    {
        $this->activatePlugin((new Word)->buildPluginSlug($pluginName));
    }

    /**
     * @Then /^Eu deveria ver o plugin "([^"]*)" ativado$/
     * @param $pluginName
     */
    public function iShouldSeeThePluginActivated($pluginName)
    {
        $this->seePluginActivated((new Word)->buildPluginSlug($pluginName));
    }

    /**
     * @Given /^O plugin "([^"]*)" está ativado$/
     * @param $pluginName
     */
    public function thePluginIsActivated($pluginName)
    {
        $pluginSlug = (new Word)->buildPluginSlug($pluginName);
        $this->haveOptionInDatabase('active_plugins', array_merge($this->grabOptionFromDatabase('active_plugins'), ["{$pluginSlug}/{$pluginSlug}.php"]));
    }

    /**
     * @When /^Eu desativo o plugin "([^"]*)"$/
     * @param $pluginName
     */
    public function iDeactivateThePlugin($pluginName)
    {
        $this->deactivatePlugin((new Word)->buildPluginSlug($pluginName));

    }

    /**
     * @Then /^Eu deveria ver o plugin "([^"]*)" desativado$/
     * @param $pluginName
     */
    public function iShouldSeeThePluginDeactivated($pluginName)
    {
        $this->seeElement('#the-list  tr[data-slug="' . (new Word)->buildPluginSlug($pluginName) . '"] td div span[class="activate"]');
    }
}