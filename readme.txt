=== Vindi WooCommerce ===
Contributors: erico.pedroso, wnarde, lyoncesar, laertejr
Website Link: https://www.vindi.com.br
Tags: vindi, subscriptions, pagamento-recorrente, cobranca-recorrente, cobrança-recorrente, recurring, site-de-assinatura, assinaturas, faturamento-recorrente, recorrencia, assinatura, woocommerce-subscriptions, vindi-woocommerce
Requires at least: 4.4
Tested up to: 5.3.2
WC requires at least: 3.0.0
WC tested up to: 3.8.1
Stable Tag: 5.5.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Venda de assinaturas de produtos e serviços pelo plugin de cobrança recorrente para o WooCommerce.

== Description ==
O **Vindi WooCommerce** oferece uma solução completa para pagamentos únicos e assinaturas com cartão de crédito e boleto utilizando o [Woocommerce Subscriptions](https://www.woothemes.com/products/woocommerce-subscriptions/). Basta ter [uma conta habilitada na Vindi](https://www.vindi.com.br/cadastro/) para começar a cobrar seus clientes.

A [Vindi](https://www.vindi.com.br/) é líder em cobrança recorrente no Brasil. Com milhares de clientes usando soluções como pagamento online, soluções de notas fiscais integradas, emissão de boletos por email e PDF, integrações com ERPs e diversos relatórios, a Vindi possibilita um sistema online completo para negócios de venda recorrente. Além disso, empresas podem usar o gateway de pagamento integrado ao billing recorrente ou para faturas avulsas.

== Installation ==

Para verificar os requisitos e efetuar a instalação do plugin, [siga as instruções na documentação oficial](https://atendimento.vindi.com.br/hc/pt-br/articles/227335608).

== Frequently Asked Questions ==

Para dúvidas e suporte técnico, entre em contato com a equipe Vindi através da nossa [central de atendimento](https://atendimento.vindi.com.br/hc/pt-br).

== Changelog ==

= 5.5.4 - 31/01/2020 =
- Ajusta verificação de status do plugin WooCommerce Subscriptions para garantir compatibilidade com novas versões

= 5.5.3 - 30/01/2020 =
- Remove exibição dos campos para assinatura Vindi quando o Woocommerce Subscriptions não está habilitado

= 5.5.2 - 09/01/2020 =
- Corrige exibição de datas no plugin

= 5.5.1 - 11/06/2019 =
- Oculta metadados (type, vindi_id e price) do frontend

= 5.5.0 - 04/06/2019 =
- Adiciona plano da Vindi para assinaturas do tipo variável

= 5.4.2 - 13/03/2019 =
- Ajusta validação dos dados da empresa durante o checkout

= 5.4.1 - 21/01/2019 =
- Adiciona opção para cobranças únicas de fretes e taxas

= 5.4.0 - 15/01/2019 =
- Adiciona compatibilidade com entrega única

= 5.3.3 - 30/11/2018 =
- Corrige instalação no ambiente Wordpress.com

= 5.3.2 - 08/10/2018 =
- Corrige cache dos métodos de pagamento
- Corrige data do próximo pagamento para as assinaturas
- Corrige comportamento dos hooks do WooCommerce Subscriptions para assinaturas Vindi (por [@cristian-rossi](https://github.com/cristian-rossi): [#102](https://github.com/vindi/vindi-woocommerce-subscriptions/pull/102))

= 5.3.1 - 05/10/2018 =
- Corrige falha na verificação do certificado SSL

= 5.3.0 - 01/10/2018 =
- Ajusta performance do checkout

= 5.2.1 - 21/09/2018 =
- Corrige fluxo de assinatura pós-cancelamento no Woocommerce

= 5.2.0 - 12/09/2018 =
- Ajusta sincronismo de assinaturas
- Corrige comportamento das transações recusadas

= 5.0.1 - 29/08/2018 =
- Corrige erro no cancelamento de assinaturas em algumas versões do PHP.

= 5.0.0 - 28/08/2018 =
- Adiciona envio das taxas do WooCommerce.
- Adiciona envio dos valores atuais do carrinho para os produtos.
- Adiciona compatibilidade com Suspensão e Reativação de assinaturas.
- Adiciona transação de verificação.
- Adiciona opção de duração personalizada para cupons de desconto.
- Remove métodos depreciados do WooCommerce 2.x.
- Remove menções ao termo 'Subscriptions'.

= 4.0.2 - 15/06/2018 =
- Ajusta verificação do certificado SSL.

= 4.0.1 - 05/04/2018 =
- Ajusta atualização de telefones.

= 4.0.0 - 22/12/2017 =
- Remove a dependência do WooCommerce Subscriptions.

= 3.0.7 - 06/12/2017 =
- Ajuste no valor de produto por quantidade.

= 3.0.6 - 03/12/2017 =
- Corrige duplicidade de pedidos em utilização com o WooCommerce Memberships.
- Ajusta cadastro de telefones.
- Ajusta cancelamento de assinaturas com WooCommerce Memberships.

= 3.0.5 - 25/09/2017 =
- Ajusta parcelamento de fatura avulsa

= 3.0.4 - 19/09/2017 =
- Adiciona parcelamento de assinaturas (deve ser configurado no painel da Vindi).
- Adiciona suporte a multilojas.
- Adiciona integração com o ambiente Sandbox da Vindi.
- Ajusta renovação de pedidos do WC Subscriptions.
- Adiciona suporte a integração com o WC Memberships.

= 3.0.3 - 14/08/2017 =
- Ajusta cupom de desconto de faturas avulsas.

= 3.0.2 - 26/07/2017 =
- Corrige tratamento de webhooks.

= 3.0.1 - 20/07/2017 =
- Ajusta renovação de pedidos.
- Adiciona registro de itens em compras avulsas.

= 3.0.0 - 16/05/2017 =
- Ajusta compatibilidade com o WooCommerce 3.0 ou superior.
- Ajusta renovação das assinaturas.
- Adiciona compatibilidade para frete.

= 1.2.2 - 06/03/2017 =
- Ajusta mensagem de envio de boleto bancário.
- Adiciona arquivos .PO e .MO para facilitar traduções.
- Adiciona select no checkout para atender vendas com a bandeira Elo.
- Ajusta exibição de periodicidade da assinatura.


= 1.2.1 - 10/10/2016 =
- Ajusta problema de comunicação com a API da Vindi.
- Ajusta o cache de métodos de pagamento para 1 hora.

= 1.2.0 - 05/10/2016 =
- Ajusta forma de renovação dos pedidos se baseando no evento bill_created da Vindi.

= 1.1.1 - 08/08/2016 =
- Remove opção de parcelamento em assinaturas variáveis.
- Corrige erro na atualização do perfil de pagamento.

= 1.1.0 - 18/07/2016 =
- Corrige problemas nos cupons de desconto para planos indeterminados.
- Adiciona seleção de status para orders com pagamento confirmado.
- Corrige webhook de período criado.
- Corrige problema de cancelamento de assinaturas e faturas em caso de falha no checkout.

= 1.0.4 - 22/06/2016 =
- Adiciona envio de telefone do checkout para a Vindi.

= 1.0.3 - 21/06/2016 =
- Adiciona suporte a configuração de ciclos para cupons de desconto.

= 1.0.2 - 08/06/2016 =
- Corrige bug de parcelamento para faturas avulsas.

= 1.0.1 - 12/05/2016 =
- Corrige bug not_found para assinaturas com frete desabilitado.
- Adiciona envio de informações do usuário pela Área do Cliente no WooCommerce Subscriptions.

= 1.0.0 - 27/04/2016 =
- Adiciona suporte a produtos e assinaturas variáveis
- Corrige exibição de planos anuais

= 0.2.7 - 14/03/2016 =
- Ajusta hook 'http_api_curl' para manipular somente request do plugin

= 0.2.6 - 25/02/2016 =
- Corrige bug ao atualizar as informações dos Produtos

= 0.2.5 - 02/02/2016 =
- Corrige bug de cancelamento da assinatura pela Área do Cliente
- Ajusta listagem dos planos para criação dos produtos
- Ajusta exibição do método de pagemento atual do Cliente na tela de checkout

= 0.2.4 - 02/02/2016 =
- Adiciona suporte para tls 1.2 e aumentando versão mínima do PHP para 5.5.19​

= 0.2.3 - 18/01/2016 =
- Ajusta verificação de dependências do plugin.
- Adiciona WooCommerce Extra Checkout Fields for Brazil como dependência.
- Ajusta bug do aviso de conexão com a Vindi.

= 0.2.2 - 05/01/2016 =
- Ajusta bug de renderização das views na configuração dos produtos.

= 0.2.1 - 21/12/2015 =
- Adiciona suporte a múltiplos produtos na mesma assinatura.

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
