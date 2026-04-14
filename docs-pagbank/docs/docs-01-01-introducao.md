# Introdução APIs PagBank

O PagBank disponibiliza uma série de serviços para que você gerencie contas e conduza pagamentos utilizando APIs.

Todas as APIs seguem o padrão REST, com requisições HTTP e respostas em JSON.

## Aplicações

As APIs do PagBank podem ser usadas de forma independente ou em conjunto.

Com elas, você consegue:

- autorizar pagamentos com captura posterior;
- pagar com boleto;
- realizar cancelamentos e estornos;
- receber notificações por webhooks;
- tokenizar cartões;
- criar pagamentos com indicação de recorrência;
- disponibilizar PIX como forma de pagamento;
- dividir pagamentos.

## Suporte durante a integração

O time PagBank pode auxiliar durante a integração e o uso das APIs.

Se precisar de ajuda, entre em contato pelo formulário oficial do PagBank.

## Serviços

### Pagamento

- [Connect](connect/docs/01-connect.md): conecta sua aplicação às contas de outros usuários PagBank para executar ações em nome deles.
- [API de Pedido](pedidos-pagamentos/pedidos/casos-de-uso/01-criar-pedido.md): organiza pedidos e pagamentos com cartão, boleto, PIX e cenários de recorrência e split.
- [API de Checkout PagBank](checkout/gerencie-checkout/01-criar-checkout.md): direciona o cliente para uma página exclusiva do PagBank para concluir o pagamento.

### Serviços sem página local neste repositório

Os itens abaixo aparecem na introdução original do portal, mas ainda não possuem documentação local correspondente neste repositório:

- API de Cadastro
- API de Pagamentos Recorrentes
- API de Transferência

## Complementares

Além dos serviços principais, o PagBank também expõe documentos complementares.

### Disponíveis neste repositório

- [Códigos de erro do Connect](connect/api/01-codigos-erro.md)

### Ainda não documentados localmente

- Chaves públicas
- Homologação
- Certificação digital
- Teste de integração
- EDI

## Próximos passos

Para começar a explorar os recursos, siga esta ordem:

1. Leia a visão geral do serviço que você quer integrar.
2. Acesse a documentação de referência da API.
3. Consulte os códigos de erro e os fluxos de autenticação, quando aplicável.

## Navegação sugerida

- [Connect](connect/docs/01-connect.md)
- [API de Pedido](pedidos-pagamentos/pedidos/casos-de-uso/01-criar-pedido.md)
- [API de Checkout PagBank](checkout/gerencie-checkout/01-criar-checkout.md)
- [Códigos de erro do Connect](connect/api/01-codigos-erro.md)
