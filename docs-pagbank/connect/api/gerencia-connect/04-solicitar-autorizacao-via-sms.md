# Solicitar autorização via SMS

`POST /oauth2/authorize/sms`

Este endpoint permite solicitar a autorização do vendedor. A confirmação da autorização é feita pelo envio de um código de autenticação via SMS para o número de telefone cadastrado na conta do vendedor.

> Acesse o [guia](/docs/connect-via-sms) para mais informações sobre os passos necessários para solicitar a autorização via SMS.

## Headers

- `X_CLIENT_ID` `string`: Identificador único fornecido no momento da criação da aplicação.
- `X_CLIENT_SECRET` `string`: Chave privada da aplicação fornecida no momento da criação.
- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Body Params

- `bank_branch` `string`: Agência bancária do cliente PagBank que deseja conceder permissão para o parceiro efetuar ações em nome dele na plataforma PagBank.
- `account_number` `string`: Número da conta bancária do cliente PagBank que deseja conceder permissão para o parceiro efetuar ações em nome dele na plataforma PagBank.

## Responses

- `200`
- `401`
- `403`

