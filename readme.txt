=== Vindi WooCommerce ===
Contributors: erico.pedroso, tales.galvao.vindi, wnarde, lyoncesar
Website Link: https://www.vindi.com.br
Tags: vindi, subscriptions, pagamento-recorrente, cobranca-recorrente, cobrança-recorrente, recurring, site-de-assinatura, assinaturas, faturamento-recorrente, recorrencia, assinatura, woocommerce-subscriptions
Requires at least: 4.4
Tested up to: 4.9.5
Stable Tag: 4.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Venda de assinaturas de produtos e serviços pelo plugin de cobrança recorrente para o WooCommerce.

== Description ==
O **Vindi WooCommerce** oferece uma solução completa para pagamentos únicos e assinaturas com cartão de crédito e boleto utilizando o [Woocommerce Subscriptions](https://www.woothemes.com/products/woocommerce-subscriptions/). Basta ter [uma conta habilitada na Vindi](https://app.vindi.com.br/prospects/new), para começar a cobrar seus clientes.

**Observações**
- Ainda não são suportados Upgrades ou Downgrades de assinaturas.

A [Vindi](http://www.vindi.com.br/) é líder em cobrança recorrente no Brasil. Com centenas de clientes usando soluções como pagamento online, soluções de notas fiscais integradas, emissão de boletos por email e PDF, integrações com ERPs e diversos relatórios, a Vindi possibilita um sistema online completo para negócios de venda recorrente. Além disso, empresas podem usar o gateway de pagamento integrado ao billing recorrente ou para faturas avulsas.

= Requisitos =

- PHP versão 5.6.x ou superior.
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
1. Ative os Webhooks abaixo na Vindi em Configurações -> Webhooks:
    - Assinatura cancelada
    - Assinatura efetuada
    - Cobrança rejeitada
    - Fatura emitida
    - Fatura paga
1. Copie a Chave API que está localizada na Vindi em Configurações -> Chaves de acesso API.
1. De volta no WooCommerce Cole a Chave API na página administrativa do plugin WooCommerce -> Configurações -> Vindi:
1. Após salvar a Chave API o Woocommerce Subscriptions vai preencher o campo com sua URL de retorno + um token de segurança, copie essa URL e cole na Vindi em Configurações -> Webhooks -> URL
1. De volta no WooCommerce -> Configurações -> Finalizar Compra -> Cartão de crédito / Boleto Bancário.
1. Em WooCommerce -> Campos do Checkout, ative Tipo de Pessoa Física e Jurídica, RG e Inscrição estadual.
1. Em WooCOmmerce -> Configurações -> Assinaturas, marque as opções "Aceitar pagamento manual" e "Desabilitar renovação automatica"
1. Em WooCommerce -> Configurações -> Produtos -> Inventário, coloque um zero na opção "Manter estoque (minutos)".

Para mais detalhes sobre a instalação de plugins no WordPress leia o tutorial [WordPress - Gerenciando Plugins](http://codex.wordpress.org/pt-br:Gerenciando_Plugins#Instalando_Plugins).

== Frequently Asked Questions ==

Para suporte ou dúvidas relacionadas ao Plugin você pode seguir pelos canais:
- [Github](https://github.com/vindi/vindi-woocommerce/issues)
- [Fórum do Plugin](https://wordpress.org/plugins/vindi-woocommerce-subscriptions/) (apenas em inglês)

Caso necessite de informações sobre a plataforma ou API por favor siga através do canal
- [Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)[Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)

== Changelog ==
= 4.0.2 - 15/06/2018
- Ajuste na verificação do certificado SSL.

= 4.0.1 - 05/04/2018 =
- Ajuste na atualização de telefones.

= 3.0.7 - 06/12/2017 =
- Ajuste no valor de produto por quantidade.

= 3.0.6 - 03/12/2017 =
- Correção de duplicidade de pedidos em utilização com o WooCommerce Memberships.
- Ajustes no cadastro de telefones.
- Ajuste no cancelamento de assinaturas com WooCommerce Memberships.

= 3.0.5 - 25/09/2017 =
- Ajuste no parcelamento de fatura avulsa

= 3.0.4 - 19/09/2017 =
- Adicionado parcelamento de assinaturas (deve ser configurado no painel da Vindi).
- Adicionado suporte a multilojas.
- Adicionada integração com o ambiente Sandbox da Vindi.
- Ajuste na renovação de pedidos do WC Subscriptions.
- Adicionado suporte a integração com o WC Memberships.

= 3.0.3 - 14/08/2017 =
- Ajustes no cupom de desconto de faturas avulsas.

= 3.0.2 - 26/07/2017 =
- Correções no tratamento de webhooks.

= 3.0.1 - 20/07/2017 =
- Ajustes na renovação de pedidos.
- Melhoria no registro de itens em compras avulsas.

= 3.0.0 - 16/05/2017 =
- Ajustes de compatibilidade com o WooCommerce 3.0 ou superior.
- Melhorias na renovação das assinaturas.
- Ajustes de compatibilidade para frete.

= 1.2.2 - 06/03/2017 =
- Ajuste na mensagem de envio de boleto bancário.
- Adicionado os arquivos .PO e .MO para facilitar traduções.
- Adicionado select no checkout para atender vendas com a bandeira Elo.
- Ajuste na exibição de periodicidade da assinatura.


= 1.2.1 - 10/10/2016 =
- Ajustes no problema de comunicação com a API da Vindi.
- Alterando o cache métodos de pagamento para 1 hora.

= 1.2.0 - 05/10/2016 =
- Alterada a forma de renovação dos pedidos se baseando no evento bill_created da Vindi.

= 1.1.1 - 08/08/2016 =
- Remove a opção de parcelamento em assinaturas variáveis.
- Corrige erro na atualização do perfil de pagamento.

= 1.1.0 - 18/07/2016 =
- Corrige problemas nos cupons de desconto para planos indeterminados.
- Adicionado seleção de status para orders com pagamento confirmado.
- Corrige o webhook de período criado.
- Corrige o problema de cancelamento de assinaturas e faturas em caso de falha no checkout.

= 1.0.4 - 22/06/2016 =
- Envio de telefone no checkout para a Vindi.

= 1.0.3 - 21/06/2016 =
- Adicionado suporte a configuração de ciclos para cupons de desconto.

= 1.0.2 - 08/06/2016 =
- Correção do bug de parcelamento para faturas avulsas.

= 1.0.1 - 12/05/2016 =
- Correção do bug not_found para assinaturas com frete desabilitado.
- Atualização de informações do usuário na Vindi pela Área do Cliente no WooCommerce Subscriptions.

= 1.0.0 - 27/04/2016 =
- Adicionado suporte a produtos e assinaturas variáveis
- Correção da exibição de planos anuais

= 0.2.7 - 14/03/2016 =
- Ajustes no hook 'http_api_curl' para manipular somente request do plugin

= 0.2.6 - 25/02/2016 =
- Correção no bug ao atualizar as informações dos Produtos

= 0.2.5 - 02/02/2016 =
- Correção no bug de cancelamento da assinatura pela Área do Cliente
- Melhorias na listagem dos planos para criação dos produtos
- Ajustes na exibição do método de pagemento atual do Cliente na tela de checkout

= 0.2.4 - 02/02/2016 =
- Adicionando suporte para tls 1.2 e aumentando versão mínima do PHP para 5.5.19​

= 0.2.3 - 18/01/2016 =
- Melhorias na verificação de dependências do plugin.
- Adicionando WooCommerce Extra Checkout Fields for Brazil como dependência.
- Ajustando o bug do aviso de conexão com a Vindi.

= 0.2.2 - 05/01/2016 =
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

Vindi WooCommerce is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Vindi WooCommerce is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Vindi WooCommerce. If not, see http://www.gnu.org/licenses/.
