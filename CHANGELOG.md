# Notas das versões


## [4.0.2 - 15/06/2018](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/4.0.2)

### Ajustado
- Ajusta verificação do certificado SSL


## [4.0.1 - 16/04/2018](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/4.0.1)

### Ajustado
- Ajusta atualização de telefones


## [4.0.0 - 21/12/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/4.0.0)

### Removido
- Remove dependência do WCS


## [3.0.7 - 06/12/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.7)

### Ajustado
- Ajusta valor de produto por quantidade


## [3.0.6 - 03/12/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.6)

### Ajustado
- Ajusta cadastro de telefones(dentro da Vindi)
- Ajusta cancelamento de assinaturas com WooCommerce Memberships

### Corrigido
- Corrige duplicidade de pedidos em utilização com o WooCommerce Memberships


## [3.0.5 - 25/09/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.5)

### Ajustado
- Ajusta parcelamento de fatura avulsa


## [3.0.4 - 19/09/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.4)

### Adicionado
- Adiciona parcelamento de assinaturas (deve ser configurado no painel da Vindi)
- Adiciona suporte a multilojas
- Adiciona integração com o ambiente Sandbox da Vindi
- Adiciona suporte a integração com o WC Memberships

### Ajustado
- Ajusta renovação de pedidos do WC Subscriptions


## [3.0.3 - 16/08/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.3)

### Ajustado
- Ajusta desconto de faturas avulsas


## [3.0.2 - 27/07/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.2)

### Corrigido
- Corrige tratamento de webhooks


## [3.0.1 - 21/07/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.1)

### Ajustado
- Ajusta renovação de pedidos
- Ajusta registro de itens em compras avulsas


## [3.0.0 - 29/05/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/3.0.0)

### Ajustado
- Ajusta compatibilidade com o WooCommerce 3.0 ou superior
- Ajusta renovação das assinaturas
- Ajusta compatibilidade para frete


## [1.2.2 - 07/03/2017](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.2.2)

### Adicionado
- Adiciona combo_box no checkout para atender vendas com a bandeira Elo

### Ajustado
- Ajusta mensagem de envio de boleto bancário
- Ajusta exibição de periodicidade para assinaturas com cobranças diferentes de mensais
- Ajusta exibição de periodicidade da assinatura


## [1.2.1 - 11/10/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.2.1)

### Ajustado
- Ajusta no problema de comunicação com a API da Vindi
- Ajusta o tempo de cache dos métodos de pagamento para 1 hora
- Ajusta forma de renovação dos pedidos se baseando no evento `bill_created` da Vindi


## [1.1.1 - 09/08/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.1.1)

### Removido
- Remove a opção de parcelamento em assinaturas variáveis

### Corrigido
- Corrige erro na atualização do perfil de pagamento


## [1.1.0 - 18/07/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.1.0)

### Adicionado
- Adiciona seleção de status para orders com pagamento confirmado

### Corrigido
- Corrige problemas nos cupons de desconto para planos indeterminados
- Corrige o webhook de período criado
- Corrige o problema de cancelamento de assinaturas e faturas em caso de falha no checkout


## [1.0.4 - 22/06/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.0.4)

### Adicionado
- Adiciona envio de telefone no checkout para a Vindi


## [1.0.3 - 21/06/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.0.3)

### Adicionado
- Adiciona suporte a configuração de ciclos para cupons de desconto


## [1.0.2 - 09/06/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.0.2)

### Corrigido
- Corrige bug de parcelamento para faturas avulsas


## [1.0.1 - 13/05/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.0.1)

### Corrigido
- Corrige bug `not_found` para assinaturas com frete desabilitado

### Ajustado
- Ajusta informações do usuário na Vindi pela área do cliente no WooCommerce Subscriptions


## [1.0.0 - 05/05/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/1.0.0)

### Adicionado
- Adiciona suporte a produtos e assinaturas variáveis

### Corrigido
- Corrige exibição de planos anuais


## [0.2.7 - 14/03/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.7)

### Ajustado
- Ajusta webhook `http_api_curl` para manipular somente request do plugin


## [0.2.6 - 25/02/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.6)

### Corrigido
- Corrige bug ao atualizar as informações dos produtos


## [0.2.5 - 02/02/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.5)

### Adicionado
- Adiciona suporte para TLS 1.2 e aumentando versão mínima do PHP para 5.6.x

### Ajustado
- Ajusta listagem dos planos para criação dos produtos
- Ajusta exibição do método de pagemento atual do cliente na tela de checkout

### Corrigido
- Corrige bug de cancelamento da assinatura pela área do cliente


## [0.2.3 - 18/01/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.3)

### Adicionado
- Adiciona WooCommerce Extra Checkout Fields for Brazil como dependência

### Ajustado
- Ajusta verificação de dependências do plugin

### Corrigido
- Corrige bug do aviso de conexão com a Vindi


## [0.2.2 - 05/01/2016](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.2)

### Corrigido
- Corrige bug de renderização das views na configuração dos produtos


## [0.2.1 - 21/12/2015](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.2.1)

### Adicionado
- Adiciona suporte a múltiplos produtos na mesma assinatura


## [0.0.1 - 14/12/2015](https://github.com/vindi/vindi-woocommerce-subscriptions/releases/tag/0.0.1)

- Versão beta
