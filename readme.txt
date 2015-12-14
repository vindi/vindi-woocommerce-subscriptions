=== WooCommerce Subscriptions Vindi ===
Contributors: erico.pedroso, tales.galvao.vindi, wnarde, lyoncesar
Website Link: https://www.vindi.com.br
Tags: vindi, subscriptions, pagamento-recorrente, cobranca-recorrente, cobrança-recorrente, recurring, site-de-assinatura, assinaturas, faturamento-recorrente, recorrencia, assinatura, woocommerce-subscriptions
Requires at least: 4.0
Tested up to: 4.4
Stable Tag: 0.0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Venda de assinaturas de produtos e serviços através do cartão de crédito e boletos pelo plugin de cobrança recorrente para o WooCommerce Subscriptions.

== Description ==

O Vindi WooCommerce Subscriptions integra a Vindi na sua loja, possibilitando com isso a gestão de assinaturas e compras avulsas pelo Word Press com a praticidade que você só encontra na Vindi.

= Requerimentos =

- PHP versão 5.4 ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/ "Plugin WooCommerce")instalado e habilitado.
- Plugin [WooCommerce Extra Checkout](https://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/ "WooCommerce Extra Checkout") Fields for Brazil instalado e habilitado.
- Plugin [WooCommerce Subscriptions instalado](https://www.woothemes.com/products/woocommerce-subscriptions/ "WooCommerce Subscriptions instalado") e habilitado.
- Utilizar HTTPS (é recomendado um certificado de 2048 bits).
- Possuir uma conta habilitada na [Vindi](https://www.vindi.com.br "Vindi").

== Installation ==

- Envie os arquivos do plugin para a pasta wp-content/plugins, ou utilize o instalador de plugins do WordPress.
- Ative o plugin.
- Ative os Webhooks abaixo na Vindi em Configurações -> Dados da empresa -> API & Webhooks:
    - Assinatura efetuada
    - Cobrança rejeitada
    - Fatura emitida
    - Fatura paga
    - Período criado
- Copie a Chave API que está localizada na Vindi em Configurações->Dados da empresa->API & Webhooks.
- De volta no WooCommerce Cole a Chave API na página administrativa do plugin WooCommerce -> Configurações -> Vindi:
- Após salvar a Chave API o Woocommerce Subscriptions vai preencher o campo com sua URL de retorno + um token de segurança, copie essa URL e cole na Vindi em Configurações -> Dados da empresa -> API & Webhooks -> URL
- De volta no WooCommerce -> Configurações -> Finalizar Compra -> Cartão de crédito / Boleto Bancário.
- Em WooCommerce -> Campos do Checkout, ative Tipo de Pessoa Física e Jurídica, RG e Inscrição estadual.
- Em WooCOmmerce -> Assinaturas, marque as opções "Aceitar pagamento manual" e "Desabilitar renovação automatica"
Na Vindi

= Suporte =

- Para suporte ao Plugin e dúvidas relacionadas ao Vindi WooCommerce Subscriptions você pode seguir pelos canais:
- [Atendimento Vindi](https://atendimento.vindi.com.br "Atendimento Vindi")
- [Github](https://github.com/vindi "Github")

== Changelog ==

= 0.0.1 - 14/12/2015 =
- Primeira versão BETA

== License ==

WooCommerce Subscriptions Vindi is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

WooCommerce Subscriptions Vindi is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with WooCommerce Subscriptions Vindi. If not, see http://www.gnu.org/licenses/.
