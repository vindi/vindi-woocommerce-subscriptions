# Descrição
O Vindi WooCommerce Subscriptions integra a Vindi na sua loja, possibilitando com isso a gestão de assinaturas e compras avulsas pelo Word Press com a praticidade que você só encontra na Vindi.

# Algumas funcionalidades suportadas
- Pagamentos recorrentes por cartão de crédito. 
- Pagamentos recorrentes por boleto bancário. 
- Pagamentos avulsos por cartão de crédito. 
- Pagamentos avulsos por boleto bancário. 
- Parcelamento de pagamentos avulsos. 
- Aceita cálculo de descontos vitalícios e frete. 
- Atualização de pedido com informações de cobranças rejeitadas. 
- Geração de novos pedidos para os próximos períodos da recorrência dos planos. 
- Recuperação de informações do cartão já cadastrado do cliente, permitindo checkout com 1 clique. 
- Area do cliente (nativo do WooCommerce Subscriptions)
- Integração com notas fiscais de produtos e serviços através de nossos parceiros.

# Observações
- O plugin foi criado para se trabalhar com o WooCommerce Subscriptions, mas também possui suporte ao WooCommerce, independe se o seu Word Press possui o WooCommerce Subscriptions ou não.
- O plugin suporta apenas 1 assinatura por vez, isso porque o carrinho do Subscriptions trata a compra de mais de uma assinatura como um pedido único e isso gera um problema em casos de cancelamento de uma delas.
- Até o momento só são suportados produtos e assinaturas simples.

# Requerimentos
- PHP versão 5.4 ou superior.
- Um site com o WordPress instalado.
- Plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/ "Plugin WooCommerce")instalado e habilitado.
- Plugin [WooCommerce Extra Checkout](https://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/ "WooCommerce Extra Checkout") Fields for Brazil instalado e habilitado.
- Plugin [WooCommerce Subscriptions instalado](https://www.woothemes.com/products/woocommerce-subscriptions/ "WooCommerce Subscriptions instalado") e habilitado.
- Utilizar um certificado SSL (é recomendado um de 2048 bits).
- Possuir uma conta habilitada na [Vindi](https://www.vindi.com.br "Vindi").

# Instalação e configuração
1. Envie os arquivos do plugin para a pasta wp-content/plugins, ou utilize o instalador de plugins do WordPress.
2. Ative o plugin.
3. Ative os Webhooks abaixo na Vindi em Configurações -> Dados da empresa -> API & Webhooks:
    - Assinatura efetuada
    - Cobrança rejeitada
    - Fatura emitida
    - Fatura paga
    - Período criado
4. Copie a Chave API que está localizada na Vindi em Configurações->Dados da empresa->API & Webhooks.
5. De volta no WooCommerce Cole a Chave API na página administrativa do plugin WooCommerce -> Configurações -> Vindi:
6. Após salvar a Chave API o Woocommerce Subscriptions vai preencher o campo com sua URL de retorno + um token de segurança, copie essa URL e cole na Vindi em Configurações -> Dados da empresa -> API & Webhooks -> URL
5. De volta no WooCommerce -> Configurações -> Finalizar Compra -> Cartão de crédito / Boleto Bancário.
6. Em WooCommerce -> Campos do Checkout, ative Tipo de Pessoa Física e Jurídica, RG e Inscrição estadual.
7. Em WooCOmmerce -> Assinaturas, marque as opções "Aceitar pagamento manual" e "Desabilitar renovação automatica"
Na Vindi

#Suporte
- Para suporte ao Plugin e dúvidas relacionadas ao Vindi WooCommerce Subscriptions você pode seguir pelos canais:
- [Atendimento Vindi](https://atendimento.vindi.com.br "Atendimento Vindi")
- [Github](https://github.com/vindi "Github")
