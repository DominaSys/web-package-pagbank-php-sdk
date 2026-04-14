# Consultar pedido

`GET /orders/{order_id}`

Este endpoint permite consultar pedidos previamente criados por meio de um identificador do pedido PagBank.

## Path Params

### `order_id` `string` `required`

Identificador do pedido PagBank.

Formato: `ORDE_XXXXXXXXXXXX`

Esse parametro e retornado na criacao do pedido como `id`.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Responses

### `200`

Consulta realizada com sucesso.

#### Response body

Retorna um objeto de pedido. Consulte a estrutura completa em [`pedidos/objeto-pedido.md`](../objeto-pedido.md).

### `400`

Requisicao invalida.

Consulte [`contas/codigos-erro.md`](../../contas/codigos-erro.md) para o padrao de erros da API.
