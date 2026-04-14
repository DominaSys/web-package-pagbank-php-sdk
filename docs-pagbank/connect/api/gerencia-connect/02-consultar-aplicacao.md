# Consultar aplicação

`GET /oauth2/application/{client_id}`

Este endpoint permite consultar detalhes de uma aplicação a partir do `client_id`. O `client_id` é fornecido no corpo da resposta da requisição de [Criar aplicação](01-criar-aplicacao.md). Na resposta, você obtém as informações fornecidas no momento da criação.

> Acesse o guia do serviço de [Connect](/docs/connect) para mais informações sobre o funcionamento e as funcionalidades disponíveis.

## Path Params

- `client_id` `string`: Identificador único fornecido no momento da criação da aplicação.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Responses

- `200`
- `400`

