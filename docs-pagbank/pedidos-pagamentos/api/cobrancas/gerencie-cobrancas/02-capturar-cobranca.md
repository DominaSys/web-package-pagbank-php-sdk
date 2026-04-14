# Capturar cobrança

`POST /charges/{char_id}/capture`

Captura uma transacao pre-autorizada por meio do identificador da cobrança fornecido pelo PagBank.

## Path Params

- `charge_id` `string`: obrigatorio. Identificador da cobrança PagBank.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Body Params

### `amount` `object`

Contem as informacoes do valor da cobrança.

#### `amount.value` `int32`

Valor a ser cobrado em centavos. Apenas numeros inteiros positivos.
Exemplo: `R$ 1.500,99 = 150099`

## Responses

- `201`
- `400`
