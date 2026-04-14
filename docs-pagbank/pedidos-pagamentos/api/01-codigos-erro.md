# Códigos de erro

Ao fazer requisições para a API de Pedidos, parâmetros incorretos podem gerar erros que impedem o fornecimento de uma resposta com os dados esperados. Nesse caso, erros serão retornados como resposta à sua requisição para ajudá-lo a entender o problema.

Esta página também reúne os erros relacionados aos endpoints de consulta de taxas de transação e de validação e armazenamento de cartão, que apoiam o processo de criação e pagamento de pedidos.

## Tratamento de erros

Você pode receber os HTTP Status `400`, `401`, `403`, `404` ou `409` quando a operação não puder prosseguir. Nesse caso, verifique o conteúdo enviado e corrija o erro antes de realizar uma nova requisição.

Existem dois padrões principais de resposta:

```json
{
  "error_messages": [
    {
      "code": "CODIGO_IDENTIFICADOR_DO_ERRO(opcional)",
      "error": "erro",
      "description": "descrição detalhada do erro",
      "parameter_name": "nome_do_parametro_que_gerou_o_erro_(opcional)"
    }
  ]
}
```

```json
{
  "error_messages": [
    {
      "code": "40001",
      "description": "required_parameter",
      "parameter_name": "payment_method.capture"
    }
  ]
}
```

## Lista de erros da API de Pedidos

| Código | Descrição | Cenário | HTTP Status |
|---|---|---|---|
| `40001` | `required_parameter` | Parâmetro obrigatório não foi informado. | `400` |
| `40002` | `invalid_parameter` | Valor informado no parâmetro é inválido ou não corresponde ao formato esperado. | `400` |
| `40003` | `parameter_unknow` | Parâmetro desconhecido ou não esperado. | `400` |
| `40004` | `rate_limit` | Limite de uso da API excedido. | `400` |
| `40005` | `idempotency_key_in_use` | Chave de idempotência já em uso. | `409` |
| `40006` | `unabled_capture` | Captura já realizada, expirada ou transação com status inválido. | `400` |
| `40007` | `unabled_refund` | Reembolso já realizado, ou valor solicitado acima do permitido. | `400` |
| `40008` | `refund_temporarily_unavailable` | Reembolso temporariamente indisponível - tente novamente mais tarde. | `400` |
| `40009` | `wallet_key_already_used` | A key da wallet já foi utilizada em outra transação. | `409` |

## Lista de erros associados à divisão da cobrança

| Código | Origem do erro | Descrição | HTTP Status |
|---|---|---|---|
| `parameter_required_missing` | `splits.receivers` | Payment split receivers must be informed. | `400` |
| `parameter_required_empty` | `splits.receivers` | Payment split receivers must be informed. | `400` |
| `parameter_required_missing` | `splits.receivers.account.id` | Receiver account id must be informed. | `400` |
| `parameter_required_empty` | `splits.receivers.account.id` | Receiver account id must be informed. | `400` |
| `invalid_id` | `receivers.account.id` | The receiver `{id da conta PagBank}` was not found. | `404` |
| `parameter_required_missing` | `splits.receivers.amount.value` | Receiver amount must be informed. | `400` |
| `invalid_amount` | `splits.receivers.amount.value` | Receiver amount must be positive and non-zero. | `400` |
| `invalid_min_receivers` | `--` | At least two receivers must be informed in a split payment. | `400` |
| `invalid_max_receivers` | `--` | Transaction must have 2 receivers. If your transaction has more than 2 receivers, send different transactions for each receiver. | `400` |
| `invalid_receivers_list` | `--` | Primary seller must be informed in the receivers list and must be the owner of the token. | `400` |
| `invalid_receivers_list` | `--` | Each split receiver must be informed only once. | `400` |
| `seller_account_required` | `--` | One or more split receivers cannot receive payments. | `400` |
| `account_inactive` | `--` | One or more receivers cannot receive payments. | `400` |
| `forbidden` | `--` | One or more receivers are not allowed to split a payment. | `403` |
| `parameter_required_missing` | `splits.method` | Payment split method must be informed. | `400` |
| `parameter_required_empty` | `splits.method` | Payment split method must be FIXED or PERCENTAGE. | `400` |
| `invalid_parameter` | `splits.method` | Payment split method must be FIXED or PERCENTAGE. | `400` |
| `invalid_parameter` | `splits.receivers.reason` | The reason for a receiver to participate in a split payment must be a maximum of 255 characters. | `400` |
| `amount_too_large` | `--` | The sum of the amounts of each receiver must equal the total amount of the transaction. | `400` |
| `amount_too_smal` | `--` | The sum of the amounts of each receiver must equal the total amount of the transaction. | `400` |
| `amount_too_large` | `--` | The sum of the percentages of each receiver must equal 100%. | `400` |
| `amount_too_small` | `--` | The sum of the percentages of each receiver must equal 100%. | `400` |
| `forbidden` | `--` | This user is not authorized to perform this query. | `403` |
| `invalid_id` | `--` | Sorry, the split data for this payment could not be found. | `404` |
| `parameter_required_missing` | `splits.source` | Source must be informed. | `400` |
| `parameter_required_empty` | `splits.source` | Currently, it is only possible to split a payment via API Charge or API Order. | `400` |
| `invalid_parameter` | `splits.source` | Currently, it is only possible to split a payment via API Charge or API Order. | `400` |
| `invalid_parameter` | `splits.receivers.configurations.liable` | The liable receiver cannot be changed when capturing a pre-authorized split payment. | `400` |
| `invalid_parameter` | `splits.receivers.configurations.liable` | Only one receiver can be liable for split payments. | `400` |
| `invalid_parameter` | `splits.receivers.configurations.liable` | Liable must be applied (`true`) for one receiver. Or none to set primary as default. | `400` |
| `invalid_parameter` | `splits.receivers.configurations.liable` | The liable configuration can only be applied for credit card payments. | `400` |
| `invalid_parameter` | `recurring` | Recurring payment is not valid for split transactions with liable configuration. | `400` |
| `invalid_parameter` | `payment_method.authentication_method.type` | 3DS authentication is not valid method for split transactions with liable configuration. | `400` |

## Lista de erros do endpoint consultar juros de uma transação

| Código | Descrição | Cenário | HTTP Status |
|---|---|---|---|
| `payment_methods_is_required` | Parameter payment_methods is a required parameter. | Ocorre quando o integrador envia o meio de pagamento utilizado vazio. | `400` |
| `payment_methods_is_invalid` | Parameter payment_methods with invalid value, see documentation. | Ocorre quando o integrador envia o meio de pagamento utilizado com um valor inválido. | `400` |
| `max_installments_no_interest_must_not_be_1` | Parameter max_installments_no_interest should be equal 0 or greater then 1. | Ocorre quando o integrador envia a quantidade de parcelas assumidas igual a 1. | `400` |
| `max_installments_no_interest_outside_range` | Parameter max_installments_no_interest should be between 0 and 12. | Ocorre quando o integrador envia a quantidade de parcelas assumidas maior ou igual a 13 ou menor que 0. | `400` |
| `max_installments_no_interest_is_invalid` | Parameter max_installments_no_interest has an invalid value, see documentation. | Ocorre quando o integrador envia a quantidade de parcelas assumidas com um valor inválido. | `400` |
| `max_installments_outside_range` | Parameter max_installments should be between 1 and 12. | Ocorre quando o integrador envia a quantidade máxima de parcelas maior ou igual a 13 ou menor que 1. | `400` |
| `max_installments_is_invalid` | Parameter max_installments with invalid value, see documentation. | Ocorre quando o integrador envia a quantidade máxima de parcelas com um valor inválido. | `400` |
| `credit_card_bin_invalid_length` | Parameter credit_card_bin should have 6 or 8 digits. | Ocorre quando o integrador envia o BIN do cartão com valor diferente de 6 ou 8 dígitos. | `400` |
| `credit_card_bin_is_invalid` | Parameter credit_card_bin should be as disposed on the credit card, see documentation. | Ocorre quando o integrador envia o BIN do cartão com um valor inválido. | `400` |
| `value_is_required` | Parameter value is a required parameter. | Ocorre quando o integrador não envia o valor original da transação. | `400` |
| `value_is_invalid` | Parameter value has an invalid value, see documentation. | Ocorre quando o integrador envia o valor original da transação com um valor inválido ou acima dos limites suportados. | `400` |
| `credit_card_bin_data_not_found` | credit_card_bin data not found. | Ocorre quando o integrador envia o BIN do cartão com um valor inválido. | `400` |
| `fees_not_configured` | User does not have configured fees for these parameters, contact your account manager. | Ocorre quando a aplicação não encontra nenhum plano de parcelamento associado ao cliente consultado. | `400` |
| `internal_server_error` | Internal server error. | Ocorre quando a aplicação identifica um erro interno. | `500` |

## Lista de erros do endpoint validar e armazenar cartão

| Código | Descrição | Cenário | HTTP Status |
|---|---|---|---|
| `card_cannot_be_stored` | Card cannot be stored. | Ocorre quando a bandeira do cartão retorna que o cartão não é válido ou não pode realizar transações. | `400` |
| `card_brand_not_supported` | Card brand is not supported. | Ocorre quando o integrador envia um cartão de uma bandeira não suportada pela API. | `400` |
| `payment_method_not_supported` | Payment method is not supported. | Ocorre quando o integrador envia um cartão de um meio de pagamento não suportado. | `400` |
| `number_is_required` | Parameter `number` is a required parameter. | Ocorre quando o integrador não envia o número do cartão ou envia o campo vazio. | `400` |
| `number_is_invalid` | Parameter `number` has an invalid value, see documentation. | Ocorre quando o integrador envia a numeração do cartão com um valor inválido. | `400` |
| `number_invalid_length` | Parameter `number` should have between 14 and 19 digits. | Ocorre quando o integrador envia a numeração do cartão com valor fora do intervalo entre 14 e 19 dígitos. | `400` |
| `exp_month_is_required` | Parameter `exp_year` is a required parameter. | Ocorre quando o integrador não envia o ano de expiração do cartão ou envia o campo vazio. | `400` |
| `exp_year_is_invalid` | Parameter `exp_year` has an invalid value, see documentation. | Ocorre quando o integrador envia o ano de expiração do cartão com um valor inválido. | `400` |
| `exp_year_invalid_length` | Parameter `exp_year` should have 4 digits. | Ocorre quando o integrador envia o ano de expiração do cartão com valor diferente de 4 dígitos. | `400` |
| `security_code_is_required` | Parameter `security_code` is a required parameter. | Ocorre quando o integrador não envia o código de segurança do cartão ou envia o campo vazio. | `400` |
| `security_code_is_invalid` | Parameter `security_code` has an invalid value, see documentation. | Ocorre quando o integrador envia o código de segurança do cartão com um valor inválido. | `400` |
| `security_code_invalid_length` | Parameter `security_code` should have 3 or 4 digits. | Ocorre quando o integrador envia o código de segurança do cartão com valor diferente de 3 ou 4 dígitos. | `400` |
| `internal_server_error` | Internal server error. | Ocorre quando a aplicação identifica um erro interno não mapeado. | `500` |
| `card_storage_not_allowed` | Action not authorized by PagBank | Ocorre quando PagBank não permitir que seja armazenado o cartão. | `404` |
