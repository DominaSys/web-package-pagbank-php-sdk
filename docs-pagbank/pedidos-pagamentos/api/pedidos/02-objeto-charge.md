# Objeto Charge

Este objeto é responsável por apresentar todos os dados disponíveis em uma cobrança, ou seja, na iniciação de um pagamento.

## Atributos

### `id` `string` `41 caracteres`

Identificador da cobrança PagBank.

Exemplo: `CHAR_67FC568B-00D8-431D-B2E7-755E3E6C66A0`

### `status` `string` `1-64 caracteres`

Status da cobrança.

Valores:

- `AUTHORIZED`: cobrança pré-autorizada.
- `PAID`: cobrança paga.
- `IN_ANALYSIS`: cobrança em análise de risco.
- `DECLINED`: cobrança negada.
- `CANCELED`: cobrança cancelada.
- `WAITING`: cobrança aguardando pagamento.

### `created_at` `datetime`

Data e horário em que a cobrança foi criada.

Exemplo: `2023-02-08T15:15:11.881-03:00`

### `paid_at` `datetime`

Data e horário em que a cobrança foi paga.

Exemplo: `2023-02-08T15:15:12.000-03:00`

### `reference_id` `string` `1-64 caracteres`

Identificador único atribuído para a cobrança.

Exemplo: `Referência da cobrança`

### `description` `string` `1-64 caracteres`

Descrição da cobrança.

Exemplo: `Descrição da cobrança`

### `amount` `object`

Contém as informações do valor a ser cobrado.

#### `amount.value` `int` `9 caracteres`

Valor a ser cobrado em centavos. Apenas números inteiros positivos.

Exemplo: `150099`

#### `amount.currency` `string` `3 caracteres`

Código de moeda ISO de três letras, em maiúsculas. Atualmente, apenas `BRL` é suportado.

Exemplo: `BRL`

#### `amount.summary` `object`

Contém um resumo de valores da cobrança.

##### `amount.summary.total` `int` `9 caracteres`

Valor total da cobrança.

Exemplo: `150099`

##### `amount.summary.paid` `int` `9 caracteres`

Valor que foi pago na cobrança.

Exemplo: `150099`

##### `amount.summary.refunded` `int` `9 caracteres`

Valor que foi devolvido da cobrança.

Exemplo: `0`

### `payment_response` `object`

Contém informações de resposta do provedor de pagamento.

#### `payment_response.code` `int` `5 caracteres`

Código PagBank que indica o motivo da resposta de autorização no pagamento.

Exemplo: `20000`

#### `payment_response.message` `string` `5-100 caracteres`

Mensagem amigável descrevendo o motivo da resposta.

Exemplo: `SUCESSO`

#### `payment_response.reference` `string` `4-20 caracteres`

NSU da autorização, caso o pagamento seja aprovado.

Exemplo: `032416400102`

#### `payment_response.raw_data` `object`

Contém informações puras vindas dos emissores e bandeiras.

##### `payment_response.raw_data.authorization_code` `int` `6 caracteres`

Código de autorização gerado no momento da tentativa.

Exemplo: `308654`

##### `payment_response.raw_data.nsu` `string` `4-20 caracteres`

Código identificador único gerado para transações de crédito e débito.

Exemplo: `032416400102`

##### `payment_response.raw_data.reason_code` `string` `2 caracteres`

Código de retorno enviado pela bandeira/emissor.

Exemplo: `70`

##### `payment_response.raw_data.merchant_advice_code` `string` `2 caracteres`

Código complementar ao `reason_code`, atualmente exclusivo para Mastercard.

Exemplo: `80`

### `payment_method` `object`

Contém as informações do método de pagamento da cobrança.

#### `payment_method.type` `enum`

Indica o método de pagamento usado na cobrança.

Valores:

- `CREDIT_CARD`
- `DEBIT_CARD`
- `BOLETO`
- `PIX`

#### `payment_method.installments` `int` `2 caracteres`

Quantidade de parcelas.

Exemplo: `06`

#### `payment_method.capture` `boolean`

Indica se a transação de cartão de crédito deve ser apenas pré-autorizada ou capturada automaticamente.

#### `payment_method.capture_before` `datetime`

Data e horário limite para captura em transações com status `AUTHORIZED`.

Exemplo: `2023-02-18T15:15:11.881-03:00`

#### `payment_method.soft_descriptor` `string` `0-22 caracteres`

Nome exibido na fatura do cliente.

Exemplo: `IntegraçãoPagBank`

#### `payment_method.card` `object`

Contém os dados de Cartão de Crédito, Cartão de Débito e Token de Bandeira.

##### `payment_method.card.id` `string` `41 caracteres`

Identificador PagBank do cartão salvo.

Exemplo: `CARD_CCFE8D12-79E9-4ADF-920B-A54E51D8DA6E`

##### `payment_method.card.number` `string` `14-19 caracteres`

Número do cartão.

Exemplo: `4111111111111111`

##### `payment_method.card.network_token` `string` `14-19 caracteres`

Número do Token de Bandeira.

Exemplo: `1234567890000000`

##### `payment_method.card.exp_month` `int` `1/2 caracteres`

Mês de expiração.

Exemplo: `12`

##### `payment_method.card.exp_year` `int` `3/4 caracteres`

Ano de expiração.

Exemplo: `2026`

##### `payment_method.card.security_code` `string` `3/4 caracteres`

Código de segurança.

Exemplo: `2026`

##### `payment_method.card.store` `boolean`

Indica se o cartão deverá ser armazenado para futuras compras.

##### `payment_method.card.brand` `string` `20 caracteres`

Bandeira do cartão.

Exemplo: `visa`

##### `payment_method.card.product` `string` `20 caracteres`

Retornado quando o cartão for do tipo `PRE_PAID`.

##### `payment_method.card.first_digits` `int` `6 caracteres`

Seis primeiros números do cartão ou token de bandeira.

Exemplo: `411111`

##### `payment_method.card.last_digits` `int` `4 caracteres`

Quatro últimos números do cartão ou token de bandeira.

Exemplo: `1111`

###### `payment_method.card.holder` `object`

Contém as informações do portador do cartão.

- `name` `string` `1-30 caracteres`: nome do portador.
- `tax_id` `string` `11/14 caracteres`: CPF ou CNPJ do portador.

#### `payment_method.token_data` `object`

Contém os dados adicionais de tokenização de rede.

- `requestor_id` `string` `11 caracteres`
- `wallet` `enum`
  - `APPLE_PAY`
  - `GOOGLE_PAY`
  - `SAMSUNG_PAY`
  - `MERCHANT_TOKENIZATION_PROGRAM`
- `cryptogram` `string` `40 caracteres`
- `ecommerce_domain` `string` `150 caracteres`
- `assurance_level` `int` `2 caracteres`

#### `payment_method.authentication_method` `object`

Contém os dados adicionais de autenticação vinculados a uma transação.

- `type` `enum`
  - `THREEDS`
  - `INAPP`
- `cavv` `string` `80 caracteres`
- `eci` `string` `2 caracteres`
- `xid` `string` `80 caracteres`
- `version` `string` `10 caracteres`
- `dstrans_id` `string` `80 caracteres`
- `status` `string` `80 caracteres`

#### `payment_method.payment_instructions` `object`

Contém instruções de pagamento, incluindo regras de multa, juros e descontos.

##### `payment_method.payment_instructions.fine` `object`

- `date` `string` `10 caracteres`
- `value` `int` `9 caracteres`

##### `payment_method.payment_instructions.interest` `object`

- `date` `string` `10 caracteres`
- `value` `int` `9 caracteres`

##### `payment_method.payment_instructions.discounts` `object`

- `date` `string` `10 caracteres`
- `value` `int` `9 caracteres`

#### `payment_method.boleto` `object`

Contém os dados para geração do boleto.

- `due_date` `string` `10 caracteres`
- `template` `string` `10 caracteres`
- `days_until_expiration` `string` `10 caracteres`

##### `payment_method.boleto.instruction_lines` `object`

- `line_1` `string` `1-75 caracteres`
- `line_2` `string` `1-75 caracteres`

##### `payment_method.boleto.holder` `object`

- `name` `string` `1-30 caracteres`
- `tax_id` `string` `11/14 caracteres`
- `email` `string` `10-255 caracteres`

###### `payment_method.boleto.holder.address` `object`

- `street` `string` `1-160 caracteres`
- `number` `string` `1-20 caracteres`
- `complement` `string` `1-40 caracteres`
- `locality` `string` `1-60 caracteres`
- `city` `string` `1-90 caracteres`
- `region` `string` `1-50 caracteres`
- `region_code` `string` `2 caracteres`
- `country` `string` `1-50 caracteres`
- `postal_code` `string` `8 caracteres`

#### `payment_method.pix` `object`

Contém os dados do pagamento Pix.

- `end_to_end_id` `string`

##### `payment_method.pix.holder` `object`

- `name` `string`
- `tax_id` `string`

### `qr_codes` `array of object`

Contém os QR Codes vinculados a um pedido.

#### `qr_codes.expiration_date` `datetime`

Data de expiração do QR Code.

Exemplo: `2021-08-29T20:15:59-03:00`

#### `qr_codes.amount` `object`

##### `qr_codes.amount.value` `int` `5 caracteres`

Valor do QR Code.

#### `qr_codes.splits` `object`

Contém informações da divisão de pagamento.

##### `qr_codes.splits.method` `enum`

Define se os valores da divisão serão informados em valores brutos ou em porcentagens do valor total da transação.

Valores:

- `FIXED`
- `PERCENTAGE`

##### `qr_codes.splits.receivers` `array of object`

Cada item contém:

###### `qr_codes.splits.receivers.amount` `object`

- `value` `int` `9 caracteres`

###### `qr_codes.splits.receivers.account` `object`

- `id` `string` `41 caracteres`

###### `qr_codes.splits.receivers.configurations` `object`

Contém informações das configurações dos recebedores participantes da divisão.

####### `qr_codes.splits.receivers.configurations.custody` `object`

Contém informações das configurações de custódia.

- `apply` `boolean`

####### `qr_codes.splits.receivers.configurations.chargeback` `object`

Contém informações das configurações de chargeback.

######## `qr_codes.splits.receivers.configurations.chargeback.charge_transfer` `object`

Contém informações da configuração de repasse de chargeback.

- `percentage` `number`

###### `qr_codes.splits.receivers.reason` `string` `255 caracteres`

### `notification_urls` `array of object`

Contém a URL que receberá as notificações relacionadas às alterações na cobrança.

### `charges` `array of object`

Representa todos os dados disponíveis em uma cobrança.

Cada item segue a mesma estrutura de um charge descrito nesta página, incluindo:

- `id`
- `status`
- `created_at`
- `paid_at`
- `reference_id`
- `description`
- `amount`
- `payment_response`
- `payment_method`
- `payment_instructions`
- `boleto`
- `pix`
- `recurring`
- `sub_merchant`
- `links`

### `recurring` `object`

Contém as informações da recorrência.

- `type` `enum`
  - `INITIAL`
  - `SUBSEQUENT`
  - `UNSCHEDULED`
  - `STANDING ORDER`
- `recurrence_id` `string` `15 caracteres`
- `original_amount` `int` `10 caracteres`

### `sub_merchant` `object`

Contém os dados do sub lojista, usado por sub-adquirentes para transações com Cartão de Crédito.

- `reference_id` `string` `15 caracteres`
- `name` `string` `60 caracteres`
- `tax_id` `string` `11 ou 14 caracteres`
- `mcc` `string` `4 caracteres`

#### `sub_merchant.address` `object`

- `street` `string` `1-160 caracteres`
- `number` `string` `1-20 caracteres`
- `complement` `string` `1-40 caracteres`
- `locality` `string` `1-60 caracteres`
- `city` `string` `1-90 caracteres`
- `region` `string` `1-50 caracteres`
- `region_code` `string` `2 caracteres`
- `country` `string` `1-50 caracteres`
- `postal_code` `string` `8 caracteres`

#### `sub_merchant.phone` `object`

- `country` `string` `3 caracteres`
- `area` `string` `2 caracteres`
- `number` `string` `8-9 caracteres`
- `type` `enum`
  - `MOBILE`
  - `BUSINESS`
  - `HOME`

### `links` `array of object`

Contém as informações de links relacionado ao recurso.

Cada item contém:

- `rel` `enum`
- `href` `string` `5-2048 caracteres`
- `media` `string` `11-64 caracteres`
- `type` `enum`
  - `GET`
  - `POST`
  - `DELETE`
  - `PUT`
