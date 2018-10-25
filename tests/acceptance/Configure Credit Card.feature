@credit_card_configuration
# language: pt
Funcionalidade: Comprar um produto com cartão de crédito
  A fim de comprar um produto com cartão de crédito
  Como um cliente
  Eu preciso ser capaz de finalizar o checkout

  Cenário: Habilito pagamento com cartão de crédito
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do WooCommerce na Tab "Pagamentos"
    E Eu clico na configuração de pagamento "Vindi - Cartão de Crédito"
    E Eu configuro pagamento no cartão de crédito
    Então Eu vejo "Suas configurações foram salvas."
    E Eu crio um dump
