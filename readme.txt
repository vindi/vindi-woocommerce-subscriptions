=== Vindi WooCommerce Subscriptions ===
Contributors: erico.pedroso, tales.galvao.vindi, wnarde, lyoncesar
Website Link: https://www.vindi.com.br
Tags: vindi, subscriptions, pagamento-recorrente, cobranca-recorrente, cobrança-recorrente, recurring, site-de-assinatura, assinaturas, faturamento-recorrente, recorrencia, assinatura, woocommerce-subscriptions
Requires at least: 4.0
Tested up to: 4.4
Beta Tag: 0.2.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Venda de assinaturas de produtos e serviços pelo plugin de cobrança recorrente para o WooCommerce Subscriptions.

== Description ==

O Vindi WooCommerce Subscriptions integra a Vindi na sua loja, possibilitando com isso a gestão de assinaturas e compras avulsas pelo WordPress com a praticidade que você só encontra na Vindi.

= Observações =
- Até o momento só são suportados produtos e assinaturas simples.

= Requisitos =

- PHP versão 5.5.19 ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/ "Plugin WooCommerce") instalado e habilitado.
- Plugin [WooCommerce Extra Checkout](https://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/ "WooCommerce Extra Checkout") Fields for Brazil instalado e habilitado.
- Plugin [WooCommerce Subscriptions instalado](https://www.woothemes.com/products/woocommerce-subscriptions/ "WooCommerce Subscriptions") instalado e habilitado.
- Certificado Digital.
- Conta ativa na [Vindi](https://www.vindi.com.br "Vindi").

== Installation ==

1. Envie os arquivos do plugin para a pasta wp-content/plugins, ou utilize o instalador de plugins do WordPress.
1. Ative o plugin.

= Configuração =
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

== Frequently Asked Questions ==

Para suporte ou dúvidas relacionadas ao Plugin você pode seguir pelos canais:
- [Github](https://github.com/vindi/vindi-woocommerce/issues)
- [Fórum do Plugin](https://wordpress.org/plugins/vindi-woocommerce-assinaturas) (apenas em inglês)

Caso necessite de informações sobre a plataforma ou API por favor siga através do canal
- [Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)[Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)

== Changelog ==

= 0.2.5 - 02/02/2015 =
- Correção no bug de cancelamento da assinatura pela Área do Cliente
- Melhorias na listagem dos planos para criação dos produtos
- Ajustes na exibição do método de pagemento atual do Cliente na tela de checkout

= 0.2.4 - 02/02/2015 =
- Adicionando suporte para tls 1.2 e aumentando versão mínima do PHP para 5.5.19​

= 0.2.3 - 18/01/2015 =
- Melhorias na verificação de dependências do plugin.
- Adicionando WooCommerce Extra Checkout Fields for Brazil como dependência.
- Ajustando o bug do aviso de conexão com a Vindi.

= 0.2.2 - 05/01/2015 =
- Ajustando o bug de renderização das views na configuração dos produtos.

= 0.2.1 - 21/12/2015 =
- Adicionado suporte a múltiplos produtos na mesma assinatura.

= 0.0.1 - 14/12/2015 =
- Primeira versão BETA.

== Screenshots ==

1. Configurações do plugin com a Vindi.
2. Configurações do plugin para cartão de crédito.
3. Configurações do plugin para boleto bancário.
4. Método de pagamento na página de finalizar o pedido para cartão de crédito.
5. Método de pagamento na página de finalizar o pedido para boleto bancário.
6. Botão de download do boleto ao concluir o pedido via boleto bancário.

== License ==

Vindi WooCommerce Subscriptions is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Vindi WooCommerce Subscriptions is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Vindi WooCommerce Subscriptions. If not, see http://www.gnu.org/licenses/.
