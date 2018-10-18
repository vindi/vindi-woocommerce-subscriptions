Feature: Installation
  In order to use the plugin
  As an administrator
  I need to be able to activate and deactivate the plugin

  Scenario: see the plugin in the plugins admin screen
    Given I am logged in as an administrator
    When I go to the plugins administration page
    Then I should see the "Vindi Woocommerce" plugin

  Scenario: deactivate the plugin
    Given I am logged in as an administrator
    And the "Vindi Woocommerce" plugin is activated
    When I go to the plugins administration page
    And I deactivate the "Vindi Woocommerce" plugin
    Then I should see the "Vindi Woocommerce" plugin deactivated

  Scenario: activate the plugin
    Given I am logged in as an administrator
    When I go to the plugins administration page
    And I activate the "Vindi Woocommerce" plugin
    Then I should see the "Vindi Woocommerce" plugin activated

