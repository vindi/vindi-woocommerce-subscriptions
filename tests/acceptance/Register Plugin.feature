@registration
# language: pt
Funcionalidade: Registrar o plugin
  A fim de registrar o plugin Vindi WooCommerce
  Como um administrador
  Eu necessito ser capaz de registar uma API KEY

  Cenário: Registro a API KEY no plugin Vindi WooCommerce
    Dado Eu estou logado como administrador
    Quando Eu vou para página de administração do WooCommerce na Tab "Vindi"
    E Eu clico no label "Ativar Sandbox"
    E Eu escrevo "API_KEY" no campo do label "Chave da API Sandbox Vindi"
    E Eu clico no texto "Salvar alterações"
    E Eu recarrego a página
    Então Eu vejo o parágrafo com texto "Conectado com sucesso!"
    E Eu crio um dump

