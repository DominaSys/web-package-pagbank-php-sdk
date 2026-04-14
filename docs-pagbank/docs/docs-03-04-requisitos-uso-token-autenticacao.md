# Token de autenticação

Os tokens de autenticação ajudam a validar a identidade de usuários e aplicações nas chamadas de API.

Eles evitam o envio repetido de credenciais sensíveis a cada requisição.

## Token de autenticação ambiente Sandbox

No Sandbox, o token é usado para testar as integrações com segurança.

Esse ambiente é apropriado para validar:

- chamadas autenticadas;
- fluxo de criação e consulta;
- comportamento da aplicação antes da produção.

## Token de autenticação ambiente de Produção

No ambiente de Produção, o token de autenticação não é suficiente para liberar todas as funcionalidades das APIs.

Algumas integrações também exigem:

- `client_id`
- `client_secret`

Essas credenciais são obtidas na API Connect.

Veja também:

- [Connect](connect/docs/01-connect.md)
- [Criar aplicação](connect/api/gerencia-connect/01-criar-aplicacao.md)

## Observação

O uso correto do token depende sempre do ambiente e do produto que você está integrando.

Consulte também:

- [Ambientes disponíveis](03-03-requisitos-uso-ambientes-disponiveis.md)
- [Chaves públicas e de idempotência](03-05-requisitos-uso-chaves-publicas-e-de-idempotencia.md)
