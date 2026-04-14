# Consultar pedido atraves de parametros

`GET /orders?charge_id={charge_id}`

Este endpoint permite consultar pedidos previamente criados por meio de parametros.

## Query Params

### `charge_id` `string` `required`

Identificador da cobranca PagBank.

Formato: `CHAR_XXXXXXXXXXXXX`

Esse parametro voce recebe na resposta da criacao do pedido como `charge.id`.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Responses

### `200`

Consulta realizada com sucesso.

#### Response body

Retorna os dados do pedido.

### `400`

Requisicao invalida.
