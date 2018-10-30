@credit_card_configuration
# language: pt
Funcionalidade: Configurar o pagamento no cartão de crédito
  A fim de configurar o pagamento no cartão de crédito
  Como um administrador
  Eu preciso ser capaz de habilitar o pagamento no cartão de crédito

  Cenário: Habilito pagamento com cartão de crédito
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do WooCommerce na Tab "Pagamentos"
    E Eu clico na configuração de pagamento "Vindi - Cartão de Crédito"
    E Eu configuro pagamento no cartão de crédito
    Então Eu vejo "Suas configurações foram salvas."
    E Eu crio um dump
