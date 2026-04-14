# Crie sua conta PagBank

Antes de utilizar as APIs do PagBank, você precisa criar uma conta PagBank.

As novas contas são alocadas inicialmente no ambiente Sandbox, para permitir testes em desenvolvimento.

## Sandbox

No Sandbox, você pode validar a integração antes de seguir para produção.

Esse ambiente é o ponto de partida para:

- testar fluxos;
- validar credenciais;
- simular operações com segurança.

## Validade do certificado digital

Os passos 2 e 3 do processo a seguir são exclusivos para as APIs de Transferência e Pix Bacen.

Para as demais APIs, esses passos podem ser desconsiderados.

### Observações importantes

- certificados digitais normalmente têm validade de 2 anos;
- após a expiração, é necessário gerar um novo certificado;
- se você gerar um novo certificado antes do vencimento, o certificado anterior será invalidado imediatamente.

## Produção

Para obter uma conta vinculada ao ambiente de Produção, é necessário concluir o processo de homologação da conta Sandbox.

Depois da homologação, você deverá repetir parte dos passos do Sandbox para obter as credenciais de produção.

## Próximos passos

Consulte também:

- [Ambientes disponíveis](03-03-requisitos-uso-ambientes-disponiveis.md)
- [Token de autenticação](03-04-requisitos-uso-token-autenticacao.md)
- [Chaves públicas e de idempotência](03-05-requisitos-uso-chaves-publicas-e-de-idempotencia.md)
