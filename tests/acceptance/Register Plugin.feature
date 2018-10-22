@register
Feature: Register Plugin
  In order to register Vindi Woocommerce
  As an administrator
  I need to be able to register API KEY

  Scenario: activate the plugin
    Given I am logged in as an administrator
    When I go to the plugins administration page
    And I activate the "Vindi Woocommerce" plugin
    Then I should see the "Vindi Woocommerce" plugin activated

  Scenario: register API KEY plugin
    Given I am logged in as an administrator
    When I type the API KEY on the field Chave da API Vindi
    Then I reload the page and I see status active "Conectado com sucesso!"

