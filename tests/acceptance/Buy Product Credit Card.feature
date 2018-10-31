@purchase_by_credit_card
# language: pt
Funcionalidade: Comprar um produto com cartão de crédito
  A fim de comprar um produto com cartão de crédito
  Como um cliente
  Eu preciso ser capaz de finalizar o checkout

  Cenário: Compro um produto
    Dado Eu estou na loja
    Quando Eu clico no texto "Polo"
    E Eu clico no texto "Comprar"
    E Eu clico no título "Ver seu Carrinho"
    E Eu clico no texto que contenha "Fechar compra"
    Então Eu vejo "MODO DE TESTES"
    E Eu zero o code
    E Eu preencho dados do cliente
    E Eu espero "3" segundos
    Então Eu vejo "Seu Pedido"
    Então Eu vejo "Polo"
    Então Eu vejo "R$25,00"
    E Eu preencho dados do cartão de crédito
    E Eu clico em Finalizar compra
    E Eu espero "10" segundos
    Então Eu vejo "Pedido recebido"
    Então Eu vejo "R$25,00"
    Então Eu vejo "Cartão de Crédito"
    Então Eu vejo "Polo × 1"
    Então Eu vejo "R$20,00"
    Então Eu confirmo a compra no gateway da Vindi
