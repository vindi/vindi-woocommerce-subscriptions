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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * @Given I am logged in as an administrator
     */
    public function iAmLoggedInAsAnAdministrator()
    {
        $this->loginAsAdmin();
    }

    /**
     * @When I go to the plugins administration page
     */
    public function iGoToThePluginsAdministrationPage()
    {
        $this->amOnPluginsPage();
    }

    /**
     * @Then I should see the :pluginName plugin
     */
    public function iShouldSeeThePlugin($pluginName)
    {
        $this->seeElement('#the-list  tr[data-slug="' . $this->buildPluginSlug($pluginName) . '"]');
    }

    /**
     * @When I activate the :pluginName plugin
     */
    public function iActivateThePlugin($pluginName)
    {
        $this->activatePlugin($this->buildPluginSlug($pluginName));
    }

    /**
     * @Then I should see the :pluginName plugin activated
     */
    public function iShouldSeeThePluginActivated($pluginName)
    {
        $this->seePluginActivated($this->buildPluginSlug($pluginName));
    }

    /**
     * @Given the :pluginNameOrFullSlug plugin is activated
     */
    public function thePluginIsActivated($pluginNameOrFullSlug)
    {
        $activePlugins = $this->grabOptionFromDatabase('active_plugins');
        $activePlugins = empty($activePlugins) ? [] : $activePlugins;
        $isFullSlug = preg_match('/^.*\\.php$/', $pluginNameOrFullSlug);
        $pluginFullSlug = [$pluginNameOrFullSlug];

        if (!$isFullSlug) {
            $pluginSlug = $this->buildPluginSlug($pluginNameOrFullSlug);
            $pluginFullSlug = ["{$pluginSlug}/{$pluginSlug}.php", "{$pluginSlug}/plugin.php"];
        }

        if (empty($activePlugins) || !count(array_intersect($pluginFullSlug, $activePlugins))) {
            $activePlugins = array_merge($activePlugins, $pluginFullSlug);
            $this->haveOptionInDatabase('active_plugins', $activePlugins);
        }
    }

    /**
     * @When I deactivate the :pluginName plugin
     */
    public function iDeactivateThePlugin($pluginName)
    {
        $this->deactivatePlugin($this->buildPluginSlug($pluginName));

    }

    /**
     * @Then I should see the :pluginName plugin deactivated
     */
    public function iShouldSeeThePluginDeactivated($pluginName)
    {
        $this->seeElement('#the-list  tr[data-slug="' . $this->buildPluginSlug($pluginName) . '"] td div span[class="activate"]');
    }

    /**
     * @param $pluginName
     * @return string
     */
    protected function buildPluginSlug($pluginName)
    {
        return implode('-', array_map('strtolower', preg_split('/[-_\\s]/', $pluginName))).'-subscriptions';
    }
}
