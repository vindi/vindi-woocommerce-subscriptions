@register
# language: pt
Funcionalidade: Registrar o plugin
  A fim de registrar o plugin Vindi WooCommerce
  Como um administrador
  Eu necessito ser capaz de registar uma API KEY

  Cenário: Ativo o plugin
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do plugin
    E Eu ativo o plugin "Vindi Woocommerce"
    Então Eu deveria ver o plugin "Vindi Woocommerce" ativado


  Cenário: Registro a API KEY no plugin Vindi WooCommerce
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do WooCommerce
    Então Eu vejo a tab "Vindi"
    E Eu clico no label "Ativar Sandbox"
    E Eu escrevo o "API_KEY" no campo do label "Chave da API Sandbox Vindi"
    E Eu clico em "Salvar alterações"
    E Eu recarrego a página
    Então Eu vejo o parágrafo com texto "Conectado com sucesso!"

