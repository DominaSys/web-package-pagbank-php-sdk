# Primeiros passos

Para começar a utilizar as APIs do PagBank, é importante entender os serviços disponíveis e os requisitos de integração.

## 1. Explore os serviços disponíveis

Antes de iniciar a integração, consulte a visão geral das APIs do PagBank e identifique quais serviços atendem ao seu caso de uso.

Veja também:

- [Introdução APIs PagBank](docs-introducao.md)

### Integração direta e de plataforma

O PagBank atende a dois perfis principais:

- **Clientes diretos**: usam principalmente a API de Pedidos para vender diretamente.
- **Clientes de plataforma**: precisam integrar também com Connect, porque executam ações em nome de vendedores.

## 2. Requisitos de uso

Antes de utilizar as APIs, você precisa seguir a jornada de integração:

- [Crie sua conta PagBank](03-01-requisitos-uso.md)
- [Ambientes disponíveis](03-03-requisitos-uso-ambientes-disponiveis.md)
- [Token de autenticação](03-04-requisitos-uso-token-autenticacao.md)
- [Chaves públicas e de idempotência](03-05-requisitos-uso-chaves-publicas-e-de-idempotencia.md)

## 3. Utilize os endpoints de teste

Depois de entender os requisitos, use os guias dos serviços disponíveis para testar a integração.

Guia local disponível neste repositório:

- [Chaves públicas](04-chaves-publicas.md)
- [Connect](connect/docs/01-connect.md)
- [API de Pedido](pedidos-pagamentos/pedidos/casos-de-uso/01-criar-pedido.md)
- [API de Checkout PagBank](checkout/gerencie-checkout/01-criar-checkout.md)

Outros serviços ainda não documentados localmente neste repositório:

- Certificado digital
- Cadastro de clientes
- Pagamentos recorrentes
- Pagar com PagBank
- EDI

## 4. Teste sua integração

Use o ambiente Sandbox para validar a integração antes de ir para produção.

Consulte também:

- [Códigos de status](02-02-primeiros-passos-codigo-status.md)

## 5. Homologação

Depois dos testes, siga o processo de homologação para liberar o uso em produção.

## Próximo passo

Se você quer continuar a leitura, siga para:

- [Códigos de status](02-02-primeiros-passos-codigo-status.md)
