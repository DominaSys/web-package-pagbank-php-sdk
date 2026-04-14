# Objeto Checkout

Este objeto representa os dados de um checkout criado no PagBank.

## Campos principais

- `id` `string`: identificador unico do checkout.
- `reference_id` `string`: identificador unico atribuido ao pedido.
- `expiration_date` `datetime`: data de expiracao do checkout.
- `customer` `object`: dados do comprador.
- `customer_modifiable` `boolean`: indica se os dados do comprador podem ser alterados.
- `items` `array of objects`: lista de itens do pedido.
- `additional_amount` `integer`: valor adicional a ser cobrado.
- `discount_amount` `integer`: valor de desconto aplicado ao pedido.
- `shipping` `object`: dados de entrega.

## Notas

- O checkout pode receber notificacoes por `payment_notification_urls` e `notification_urls`.
- O fluxo pode incluir configuracoes de meios de pagamento e recorrencia.
