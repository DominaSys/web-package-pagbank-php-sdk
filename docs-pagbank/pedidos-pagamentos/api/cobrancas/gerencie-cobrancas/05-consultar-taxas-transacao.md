# Consultar taxas de uma transacao

`GET /charges/fees/calculate`

Consulta as taxas de uma transacao para um ou mais meios de pagamento.

## Query Params

- `payment_methods` `array of strings`: obrigatorio. Meios de pagamento a simular.
- `value` `int32`: obrigatorio. Valor original da transacao.
- `max_installments` `int32`: quantidade maxima de parcelas permitidas.
- `max_installments_no_interest` `int32`: quantidade de parcelas assumidas pelo vendedor.
- `credit_card_bin` `int32`: BIN do cartao, ou seja, os seis primeiros numeros do cartao.
- `show_seller_fees` `boolean`: habilita a visualizacao da taxa paga pelo vendedor.
- `account_id` `string`: conta usada para consultar o MCC da simulacao.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.
- `Content-Type` `string`: tipo do recurso, normalmente `application/json`.

## Responses

- `200`
- `400`
