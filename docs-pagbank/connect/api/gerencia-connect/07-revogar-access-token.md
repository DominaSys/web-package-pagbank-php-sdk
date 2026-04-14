# Revogar access token

`POST /oauth2/revoke`

Este endpoint permite revogar o acesso à conta de um usuário. O `access_token` existente perde sua validade.

> Acesse o guia do serviço de [Connect](/docs/connect) para mais informações sobre o funcionamento e as funcionalidades disponíveis.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.
- `X_CLIENT_ID` `string`: Identificador único fornecido no momento da criação da aplicação.
- `X_CLIENT_SECRET` `string`: Chave privada da aplicação fornecida no momento da criação.

## Body Params

- `token_type_hint` `string`: Tipo de token que está sendo enviado. Valores aceitos: `access_token` e `refresh_token`.
- `token` `string`: Token correspondente ao `token_type_hint`, que será utilizado para fazer a revogação.

## Responses

- `200`
- `400`

