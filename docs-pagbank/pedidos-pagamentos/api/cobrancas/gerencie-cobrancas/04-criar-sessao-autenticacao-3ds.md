# Criar sessao de autenticacao 3DS

`POST https://sandbox.sdk.pagseguro.com/checkout-sdk/sessions`

Cria uma sessao usada no fluxo de autenticacao 3DS.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.
- `Content-Type` `string`: tipo do recurso, normalmente `application/json`.

## Responses

- `200`
- `400`
