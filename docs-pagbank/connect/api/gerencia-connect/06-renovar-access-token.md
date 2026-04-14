# Renovar access token

`POST /oauth2/refresh`

Este endpoint permite renovar o `access_token`. Você fornece o `refresh_token`, obtido ao utilizar o endpoint [Obter access token](05-obter-access-token.md) ou da última renovação realizada por este mesmo endpoint. Ao usar esse fluxo, você recebe:

- Um novo `access_token` com nova data de expiração.
- Um novo `refresh_token` para a próxima renovação.

> Você pode fazer uma chamada para atualizar o token antes de chamar um serviço para executar ações em nome do usuário.
>
> Acesse o guia do serviço de [Connect](/docs/connect) para mais informações sobre o funcionamento e as funcionalidades disponíveis.
>
> Sempre que um `refresh_token` é utilizado para renovar o `access_token`, um novo `refresh_token` é gerado. O token anterior é automaticamente invalidado e o novo `refresh_token` deve ser usado na próxima renovação.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.
- `X_CLIENT_ID` `string`: Identificador único fornecido no momento da criação da aplicação.
- `X_CLIENT_SECRET` `string`: Chave privada da aplicação fornecida no momento da criação.

## Body Params

- `grant_type` `string`: Tipo de solicitação desejada. Valor padrão `refresh_token`.
- `refresh_token` `string`: Token de identificação para renovação do acesso concedido. A partir dele, um novo `access_token` e um novo `refresh_token` serão fornecidos.

## Responses

- `200`

