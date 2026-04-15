# Alterar chave pública

`PUT /public-keys/card`

Este endpoint permite que você altere a chave pública vinculada a sua conta. A chave pública antiga continuará válida por 7 dias após você realizar a atualização.

> Acesse o guia do serviço de [chaves públicas](/docs/chaves-publicas) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Headers

| Header | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `Authorization` | string | Sim | Token de autenticação. Deve ser enviado no formato `Bearer <token>`. |

## Responses

| Status | Descrição |
| --- | --- |
| `200` | Atualização realizada com sucesso. |
| `400` | Requisição inválida. |
