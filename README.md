![alt text align:center](https://www.vindi.com.br/image/vindi-logo-transparente.png "Vindi")

# Vindi - WooCommerce Subscriptions

[![Última Versão no WordPress][ico-version]][link-version]
[![Licença do Software][ico-license]](license.txt)
[![Avaliação do Plugin][ico-rates]][link-rates]
[![Downloads no Total][ico-downloads]][link-downloads]

## Descrição
O Vindi WooCommerce Subscriptions integra a Vindi na sua loja, possibilitando com isso a gestão de assinaturas e compras avulsas pelo WordPress com a praticidade que você só encontra na Vindi.

# Observações
- Até o momento só são suportados produtos e assinaturas simples.

# Requisitos
- PHP versão 5.4 ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/ "Plugin WooCommerce") instalado e habilitado.
- Plugin [WooCommerce Extra Checkout](https://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/ "WooCommerce Extra Checkout") Fields for Brazil instalado e habilitado.
- Plugin [WooCommerce Subscriptions instalado](https://www.woothemes.com/products/woocommerce-subscriptions/ "WooCommerce Subscriptions") instalado e habilitado.
- Certificado Digital.
- Conta ativa na [Vindi](https://www.vindi.com.br "Vindi").

# Instalação
1. Envie os arquivos do plugin para a pasta wp-content/plugins, ou utilize o instalador de plugins do WordPress.
1. Ative o plugin.

# Configuração
1. Ative os Webhooks abaixo na Vindi em Configurações -> Dados da empresa -> API & Webhooks:
    - Assinatura efetuada
    - Cobrança rejeitada
    - Fatura emitida
    - Fatura paga
    - Período criado
1. Copie a Chave API que está localizada na Vindi em Configurações->Dados da empresa->API & Webhooks.
1. De volta no WooCommerce Cole a Chave API na página administrativa do plugin WooCommerce -> Configurações -> Vindi:
1. Após salvar a Chave API o Woocommerce Subscriptions vai preencher o campo com sua URL de retorno + um token de segurança, copie essa URL e cole na Vindi em Configurações -> Dados da empresa -> API & Webhooks -> URL
1. De volta no WooCommerce -> Configurações -> Finalizar Compra -> Cartão de crédito / Boleto Bancário.
1. Em WooCommerce -> Campos do Checkout, ative Tipo de Pessoa Física e Jurídica, RG e Inscrição estadual.
1. Em WooCOmmerce -> Assinaturas, marque as opções "Aceitar pagamento manual" e "Desabilitar renovação automatica"
Na Vindi

## Dúvidas
Caso necessite de informações sobre a plataforma ou API por favor siga através do canal [Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)

## Contribuindo
Por favor, leia o arquivo [CONTRIBUTING.md](CONTRIBUTING.md).

Caso tenha alguma sugestão ou bug para reportar por favor nos comunique através das [issues](./issues).

## Changelog
Todas as informações sobre cada release pode ser  [CHANGELOG.md](CHANGELOG.md).

## Créditos
- [Vindi](https://github.com/vindi)
- [Todos os Contribuidores](https://github.com/vindi/vindi-woocommerce-subscriptions/contributors)

## Licença
GNU GPLv3. Por favor, veja o [Arquivo de Licença](license.txt) para mais informações.

[ico-version]: https://img.shields.io/wordpress/plugin/v/vindi-woocommerce-subscriptions.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat-square
[ico-rates]: https://img.shields.io/wordpress/plugin/r/vindi-woocommerce-subscriptions.svg?style=flat-square
[ico-downloads]: https://img.shields.io/wordpress/plugin/dt/vindi-woocommerce-subscriptions.svg?style=flat-square
[link-version]: https://wordpress.org/plugins/vindi-woocommerce-subscriptions/
[link-rates]: https://wordpress.org/support/view/plugin-reviews/vindi-woocommerce-subscriptions
[link-downloads]: https://wordpress.org/plugins/vindi-woocommerce-subscriptions/stats/
