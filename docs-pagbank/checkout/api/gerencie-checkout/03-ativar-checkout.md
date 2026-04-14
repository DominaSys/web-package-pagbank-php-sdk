# Ativar Checkout

`POST /checkouts/{checkout_id}/activate`

Ativa um checkout previamente inativado.

Utilize este endpoint para ativar um checkout que esteja com status `INACTIVE`.

> **Checkout `EXPIRED`**
> Não é possível ativar um checkout depois que ele expirou.

> Acesse o guia do serviço de [checkout](../../docs/docs-09-02-checkout-e-link-de-pagamento.md) para mais informações sobre o funcionamento e as funcionalidades disponíveis.

## Path Params

- `checkout_id` `string`: Identificador do checkout criado pelo PagBank. Formato `CHEC_XXXXXXXXXXXX`. Você recebe esse parâmetro na resposta da criação do checkout como `id`.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Responses

- `200`: Checkout ativado com sucesso.
- `400`: Requisição inválida.
