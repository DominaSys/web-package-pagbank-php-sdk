# Códigos de erro

Esta pagina lista os erros da API de Checkout do PagBank.

## Tratamento de erros

Os retornos mais comuns usam HTTP `400`, `401`, `403`, `404`, `406` e `415`.

## Lista de erros

- `invalid_authorization_header`
- `allowlist_access_required`
- `access_denied`
- `invalid_request_body`
- `field_cannot_be_empty`
- `invalid_length`
- `invalid_list_element_length`
- `invalid_enum`
- `invalid_format`
- `invalid_value`
- `invalid_list_element_value`
- `invalid_shipping_config`
- `checkout_not_found`
- `checkout_expired`
- `invalid_discount_value`
- `invalid_cart_total_value`
- `invalid_calculate_shipping_config`
- `inconsistent_brand_configurations`
- `invalid_payment_method`
- `field_brands_is_not_compatible_with_payment_method`
- `invalid_config_option`
- `repeated_options`
- `inconsistent_checkout_payment_method_configs`
- `invalid_fixed_shipping_configuration`
- `invalid_unmodifiable_address`
- `recurrent_checkout_disabled`
- `more_than_one_item_not_allowed_for_recurrence`
- `shipping_type_not_allowed_for_recurrence`
- `payment_method_not_allowed_for_recurrence`
- `installments_not_allowed_for_recurrence`
