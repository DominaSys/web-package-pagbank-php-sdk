# Consultar divisao da cobranca

`GET /splits/{split_id}`

Este endpoint permite fazer uma consulta dos dados da divisao da cobranca de um pedido.

> Este endpoint usa uma URL base diferente dos demais endpoints da API Connect: `https://sandbox.api.pagseguro.com/splits/`

## Path Params

### `split_id` `string` `required`

Identificador unico da divisao da cobranca.

Voce recebe essa informacao quando faz a cobranca de um pedido ou quando cria e paga o pedido na mesma operacao.

## Headers

- `Authorization` `string`: obrigatorio, token do recebedor primario.

## Responses

### `200`

Consulta realizada com sucesso.

#### Response body

Retorna os dados da divisao da cobranca.

### `400`

Requisicao invalida.
