# Inativar Checkout

`POST /checkouts/{checkout_id}/inactivate`

Inativa um checkout previamente criado.

Caso você enfrente algum contratempo em suas vendas ou por qualquer outro motivo, você pode utilizar este endpoint para desativar um checkout. Um checkout pode ser inativado permanentemente ou temporariamente de acordo com sua necessidade. Se necessário, depois você pode reativá-lo utilizando o endpoint [Ativar Checkout](/reference/ativar-checkout).

> Acesse o guia do serviço de [checkout](../../docs/docs-09-02-checkout-e-link-de-pagamento.md) para mais informações sobre o funcionamento e as funcionalidades disponíveis.

## Path Params

- `checkout_id` `string`: id do checkout. Formato `CHEC_XXXXXXXXXXXX`.

## Headers

- `Authorization` `string`: Bearer token.

## Responses

- `200`: Checkout inativado com sucesso.
- `400`: Requisição inválida.
