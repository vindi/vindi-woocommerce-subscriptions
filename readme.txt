=== Vindi WooCommerce Assinaturas ===
Contributors: erico.pedroso, tales.galvao.vindi, wnarde
Website Link: https://www.vindi.com.br
Tags: vindi, subscription, membership, pagamento-recorrente, cobranca-recorrente, cobrança-recorrente, recurring, site-de-assinatura, assinaturas, faturamento-recorrente, recorrencia, assinatura, subscription-billing, cielo, elavon, rede, redecard, boleto, boleto-bancario, cartao-de-credito, bank-slip, credit-card, woocommerce
Requires at least: 4.0
Tested up to: 4.4
Stable Tag: 0.0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Venda assinaturas de produtos e serviços através do cartão de crédito e boletos pelo plugin de cobrança recorrente Vindi WooCommerce Assinaturas.

== Description ==

O **Vindi WooCommerce Assinaturas** é um plugin que viabiliza a cobrança recorrente de mensalidades, planos e assinaturas com cartão de crédito e boleto. Clubes, Sites de Assinatura de Produtos, SaaS (softwares online), Hospedagens, e-Learnings, Cursos e Escolas, Jogos Online, Editoras, Serviços e toda empresa que precisa cobrar mensalmente seus clientes, podem usar o plugin de assinaturas e cobrança recorrente da [Vindi](http://www.vindi.com.br/). Basta ter [uma conta habilitada na Vindi](https://app.vindi.com.br/prospects/new), para começar a cobrar seus clientes.

A [Vindi](http://www.vindi.com.br/) é líder em cobrança recorrente no Brasil. Com centenas de clientes usando soluções como pagamento online, soluções de notas fiscais integradas, emissão de boletos por email e PDF, integrações com ERPs e diversos relatórios, a Vindi possibilita um sistema online completo para negócios de venda recorrente. Além disso, empresas podem usar o gateway de pagamento integrado ao billing recorrente ou para faturas avulsas.

= Propriedade e Desenvolvimento =
O plugin **Vindi WooCommerce Assinaturas** foi desenvolvido pela Vindi, sendo de exclusividade da empresa. Para uso do plugin e suas funcionalidades basta [habilitar uma conta na Vindi](https://app.vindi.com.br/prospects/new), solicitando permissão para começar a instalação e integração das melhores ferramentas para seu negócio de assinaturas.

A integração segue os parâmetros e arquitetura da API de pagamento recorrente da Vindi, que pode ser consultada através da página [API DE PAGAMENTO RECORRENTE](http://atendimento.vindi.com.br/hc/pt-br/articles/203020644).

Seu código está distribuído sob os termos do [GNU GPLv3](http://www.gnu.org/licenses/gpl-3.0.html).

= Requerimentos =
- PHP versão **5.4** ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/) instalado e habilitado.
- Plugin [WooCommerce Extra Checkout Fields for Brazil](https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/) instalado e habilitado.
- Utilizar um certificado SSL (é recomendado um de 2048 bits).
- [Possuir uma conta habilitada na Vindi](https://app.vindi.com.br/prospects/new).

== Installation ==

- Envie os arquivos do plugin para a pasta `wp-content/plugins`, ou utilize o instalador de plugins do WordPress.
- Ative o plugin.
- Configure o plugin na página de administração do WordPress em:
    - *WooCommerce -> Configurações -> Finalizar Compra -> Vindi - Cartão de Crédito* e
    - *WooCommerce -> Configurações -> Finalizar Compra -> Vindi - Boleto Bancário*.
- Em *WooCommerce -> Campos do Checkout*, ative Tipo de Pessoa Física e Jurídica, RG e Inscrição estadual.
- Para mais detalhes sobre a instalação de plugins no WordPress leia o tutorial [WordPress - Gerenciando Plugins] (http://codex.wordpress.org/pt-br:Gerenciando_Plugins#Instalando_Plugins).

== Frequently Asked Questions ==

= Quais são os requisitos para utilizar o plugin? =
- PHP versão **5.4** ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/) instalado e habilitado.
- Plugin [WooCommerce Extra Checkout Fields for Brazil](https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/) instalado e habilitado.
- Utilizar um certificado SSL (é recomendado um de 2048 bits).
- [Possuir uma conta habilitada na Vindi](https://app.vindi.com.br/prospects/new).

= Quais são os métodos de pagamento aceitos? =
O plugin aceita atualmente pagamentos via cartão de crédito e boleto bancário.

* Bancos disponíveis para emissão de boletos:
    * Bradesco
    * Itaú
    * Santander
    * HSBC
    * Banco do Brasil
* Bandeiras de cartões de crédito aceitas:
    * Visa
    * MasterCard
    * American Express
    * Diners Club
    * Elo
    * Hiper
    * Hipercard
* Operadoras de cartão (adquirentes) disponíveis:
    * Cielo
    * Rede
    * Elavon
    * Stone
    * Global Payments

= Esta solução de pagamentos é segura? =
A Vindi possui a certificação PCI DSS. Isso significa que todas as transações são seguras e cumprem exigências de acordo com o padrão estabelecido pelo PCI. Caso deseje, [leia mais sobre segurança](http://www.vindi.com.br/recursos/pci-compliance/) ou entre em contato para qualquer esclarecimento.

= O cliente deverá informar os dados do cartão de crédito todo mês? =
Não, uma vez informados os dados de cartão de crédito, os mesmos são armazenados nos servidores seguros da Vindi e utilizados para a realização de cobranças conforme a assinatura é renovada. [Entenda mais sobre o funcionamento](https://blog.vindi.com.br/entenda-as-vantagens-de-um-gateway-de-pagamento-recorrente/).

= O valor de todos os períodos da assinatura serão descontados do limite do cartão de crédito do cliente? =
Não, essa é a vantagem de utilizar uma solução de cobranças recorrentes como a Vindi. O valor da assinatura é cobrado a cada período, diferentemente de um parcelamento, portanto somente este valor será descontado do limite do cliente. Leia [este artigo para saber mais sobre a diferença entre parcelamento e recorrência](http://atendimento.vindi.com.br/hc/pt-br/articles/204146444-Parcelamento-e-recorr%C3%AAncia).

= O cliente deverá acessar o site todo mês para obter os boletos? =
Não. O cliente receberá os boletos via e-mail no ato da renovação da assinatura.

= Por que preciso de um certificado SSL? =
Para lidar com informações sensíveis como as de cartão de crédito, é estritamente necessário utilizar um certificado de segurança SSL, que irá criptografar os dados trocados entre os usuários e seu site, não permitindo que essa informação seja interceptada por terceiros.

= Meu site irá guardar dados sensíveis de cartão de crédito sobre meus clientes? =
Não, nenhum dado sensível é armazenado diretamente em seu site. Essas informações vão direto para os servidores seguros da Vindi, que são responsáveis por guardá-los e processá-los. Basicamente, seu site tem acesso apenas aos status dos pagamentos de seus clientes.

= Como saberei se os pagamentos foram confirmados? =
Ao configurar o plugin, é possível adicionar um webhook ao painel da Vindi, que será responsável por avisar ao plugin sobre as mudanças de status de pagamento das assinaturas e vendas avulsas. Assim, os status dos pedidos dentro do WooCommerce serão alterados para refletir as mudanças processadas pela plataforma da Vindi.

Além disso, todas as informações, bem como diversos relatórios, estão disponíveis dentro do painel de usuário da Vindi.

= É possível fazer pagamentos simples, ou seja, que não sejam recorrentes/assinaturas? =
Sim, você pode utilizar o plugin para processamento de vendas avulsas, que não sejam assinaturas, assim como em um gateway tradicional. Assim, você pode oferecer tanto assinaturas quanto produtos normais em seu site.

= É possível parcelar as vendas simples? =
Sim, o plugin fornece a opção de parcelamento de vendas avulsas/simples. Você pode informar o número máximo de parcelas, conforme o que foi acordado entre você e as operadoras de cartão (adquirentes).

= É possível adicionar o valor do frete ao preço da assinatura? =
Sim. Utilize qualquer método de cálculo de frete desejado, como o [WooCommerce Correios](https://wordpress.org/plugins/woocommerce-correios/). O plugin adicionará o valor do frete ao valor da assinatura. Caso não deseje cobrar frete, utilize a opção Frete Grátis do próprio WooCommerce.

= É possível utilizar cupons de desconto na assinatura? =
Sim. Porém, no momento, ao adicionar um desconto, o valor será refletido em **todas as cobranças**. O suporte a cupons para períodos customizados será adicionado futuramente.

= É possível fazer estornos ou cancelar assinaturas pelo plugin? =
No momento ainda não é possível fazer essa configuração pelo plugin, mas é totalmente possível acessar a plataforma de usuário da Vindi e ajustar as assinaturas e pagamentos conforme necessário.

= Trabalho com uma recorrência que não é mensal, é possível configurar o plugin para trabalhar com meu período de = cobrança customizado?
Sim, você pode escolher o período que quiser através da plataforma de usuário da Vindi, que é a responsável pelo cobrança das recorrências.

= Ainda tendo dúvidas, como posso obter suporte sobre o plugin ou sobre a plataforma da Vindi? =
Para suporte ao plugin e dúvidas relacionadas à plataforma da Vindi, você pode nos encontrar pelos seguintes canais:
* [Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)
* [Github](https://github.com/vindi/vindi-woocommerce/issues)
* [Fórum do Plugin](https://wordpress.org/plugins/vindi-woocommerce-assinaturas) (apenas em inglês)

= Suporte =

Para suporte ao Plugin e dúvidas relacionadas ao **Vindi WooCommerce Assinaturas** você pode seguir pelos canais:

- [Atendimento Vindi](http://atendimento.vindi.com.br/hc/pt-br)
- [Github](https://github.com/vindi/vindi-woocommerce/issues)
- [Fórum do Plugin](https://wordpress.org/plugins/vindi-woocommerce-assinaturas) (apenas em inglês)

== Screenshots ==

1. Configurações do plugin para cartão de crédito.
2. Configurações do plugin para boleto bancário.
3. Método de pagamento na página de finalizar o pedido para cartão de crédito.
4. Método de pagamento na página de finalizar o pedido para boleto bancário.
5. Botão de download do boleto ao concluir o pedido via boleto bancário.

== Changelog ==

= 2.3.4 - 10/11/2015 =
- Melhorias na validação da configuração da chave API.

= 2.3.3 - 22/10/2015 =
- Melhorias na comunicação com os webhooks.

= 2.3.2 - 30/07/2015 =
- Melhorias na comunicação com a API da Vindi.
- Arrumado erro com a constante 'WC_VINDI_VERSION'

= 2.3.1 - 06/07/2015 =
- Adicionada opção para enviar informações para emissão de NFe's.

= 2.3.0 - 08/06/2015 =
- Adicionada validação tornando necessária a utilização de um certificado SSL para realização de cobranças em produção.
- Divisão dos métodos de pagamento Cartão de Crédito e Boleto Bancário em dois gateways distintos, podendo ser habilitados separadamente.
- Cobranças rejeitadas agora são informadas no pedido. Caso todas as tentativas de cobrança sejam rejeitadas, o status do pedido é atualizado para "Falhado".
- Adicionado token de segurança aos webhooks.
- Adicionada validação de quantidade ao atualizar itens do carrinho para evitar a compra de mais de uma assinatura ao mesmo tempo.
- Retrabalhada a validação do campo CVV em cartões.
- Removida a dependência CSS. As regras foram simplificadas e aplicadas de forma *inline*.
- Removido logo da Vindi das opções de pagamento. Com a divisão de gateways, tornou-se desnecessário.
- Algumas consultas a API foram adicionados ao cache do WordPress (*transients*) para ganho de performance.
- Adicionado o plugin [WooCommerce Extra Checkout Fields for Brazil](https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/) à lista de dependências.
- Adicionada mensagem de alerta caso a conta Vindi esteja em modo Trial.
- Grande Refatoração do Código para manter o mesmo estilo de código do WordPress.
- Outras pequenas melhorias e validações.

= 2.2.0 - 08/05/2015 =
- Retrabalhada a forma como o status do pedido é definido quando a confirmação de pagamento é recebida.
- Informações de endereço e documento agora são enviados para a criação do usuário na Vindi, para permitir a emissão de notas fiscais.

= 2.1.0 - 26/04/2015 =
- Pedidos agora reduzem o estoque de produtos em assinaturas e vendas avulsas.

= 2.0.0 - 05/03/2015 =
- Nova versão do plugin, com suporte a nova API, aceitando assinaturas e vendas avulsas.

== License ==

Vindi WooCommerce Assinaturas is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Vindi WooCommerce Assinaturas is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Vindi WooCommerce Assinaturas. If not, see http://www.gnu.org/licenses/.
