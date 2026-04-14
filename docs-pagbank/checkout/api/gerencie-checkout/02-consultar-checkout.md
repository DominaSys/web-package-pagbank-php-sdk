# Consultar Checkout

`GET /checkouts/{checkout_id}`

Permite consultar um checkout e todos os pagamentos associados a ele. Você pode usar paginação para retornar a quantidade desejada de pagamentos por requisição.

> Acesse o guia do serviço de [checkout](../../docs/docs-09-02-checkout-e-link-de-pagamento.md) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Path Params

- `checkout_id` `string`: Identificador do checkout criado pelo PagBank. Formato `CHEC_XXXXXXXXXXXX`. Você recebe esse parâmetro na resposta da criação do checkout como `id`.

## Query Params

- `offset` `string`: Item inicial na consulta.
- `limit` `string`: Quantidade de itens retornados nessa página. Máximo de 100. Se não informado, o valor padrão é 10.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Responses

- `200`: Checkout consultado com sucesso.
- `400`: Requisição inválida.
