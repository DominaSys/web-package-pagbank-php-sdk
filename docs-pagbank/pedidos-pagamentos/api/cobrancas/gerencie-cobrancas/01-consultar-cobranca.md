# Consultar cobrança

`GET /charges/{charge_id}`

Consulta o status de uma cobrança por meio do identificador gerado pelo PagBank.

## Path Params

- `charge_id` `string`: obrigatorio. Identificador da cobrança PagBank.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Responses

- `200`
- `400`
