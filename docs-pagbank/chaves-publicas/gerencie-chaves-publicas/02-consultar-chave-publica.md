# Consultar chave pública

`GET /public-keys/card`

Este endpoint permite que você consulte a chave pública existente na sua conta.

> Acesse o guia do serviço de [chaves públicas](/docs/chaves-publicas) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Headers

| Header | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `Authorization` | string | Sim | Token de autenticação. Deve ser enviado no formato `Bearer <token>`. |

## Responses

| Status | Descrição |
| --- | --- |
| `200` | Consulta realizada com sucesso. |
| `400` | Requisição inválida. |
