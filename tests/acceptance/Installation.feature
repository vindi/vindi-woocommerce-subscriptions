@initialize
# language: pt
Funcionalidade: Instalação
  A fim de utilizar o plugin
  Como um administrador
  Eu necessito ser capaz de ativar e desativar o plugin

  Cenário: Vejo o plugin na tela de administração do plugin
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do plugin
    Então Eu deveria ver o plugin "Vindi WooCommerce"

  Cenário: Desativo o Plugin
    Dado Eu estou logado como administrador
    E O plugin "Vindi Woocommerce" está ativado
    Quando Eu vou para página de administração do plugin
    E Eu desativo o plugin "Vindi Woocommerce"
    Então Eu deveria ver o plugin "Vindi Woocommerce" desativado

  Cenário: Ativo o  plugin
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do plugin
    E Eu ativo o plugin "Vindi Woocommerce"
    Então Eu deveria ver o plugin "Vindi Woocommerce" ativado

