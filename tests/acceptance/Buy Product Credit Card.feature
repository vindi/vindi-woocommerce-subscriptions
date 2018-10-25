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
    E Eu registro uma compra
    Então Eu vejo "MODO DE TESTES"

