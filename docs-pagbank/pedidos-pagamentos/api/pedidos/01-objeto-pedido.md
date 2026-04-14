# Objeto Pedido

Esse objeto representa o fechamento do carrinho de compras. Contém informações que identificam o que está sendo adquirido, o comprador, endereço de entrega e demais informações relevantes.

## Atributos

### `id` `string` `41 caracteres`

Identificador do pedido PagBank.

Exemplo: `ORDE_F87334AC-BB8B-42E2-AA85-8579F70AA328`

### `reference_id` `string` `1-64 caracteres`

Identificador único atribuído para o pedido.

Exemplo: `ex-00001`

### `customer` `object`

Contém informações do cliente que fará pagamentos usando o serviço PagBank.

#### `customer.name` `string` `1-120 caracteres`

Nome do cliente.

Exemplo: `João Souza`

#### `customer.email` `string` `10-255 caracteres`

E-mail do cliente.

Exemplo: `joaosouza@gmail.com`

#### `customer.tax_id` `string` `11/14 caracteres`

Documento de identificação pessoal do cliente. Obrigatório.

Exemplo: `12345678910`

#### `customer.phone` `array of object`

Contém uma lista de telefones do cliente.

Cada item contém:

- `customer.phone.country` `int` `2 caracteres`: código de operadora do país (DDI).
- `customer.phone.area` `int` `2 caracteres`: código de operadora local (DDD).
- `customer.phone.number` `int` `8-9 caracteres`: número do telefone.
- `customer.phone.type` `string` `enum`: tipo de telefone.
  - `MOBILE`: telefone celular.
  - `BUSINESS`: telefone comercial.
  - `HOME`: telefone residencial.

### `items` `array of object`

Contém as informações dos itens inseridos no pedido.

Cada item contém:

- `items.name` `string` `1-200 caracteres`: nome dado ao item.
- `items.quantity` `int` `5 caracteres`: quantidade referente ao item.
- `items.unit_amount` `int` `9 caracteres`: valor do item.
- `items.reference_id` `string` `1-255 caracteres`: identificador único atribuído ao item.

### `shipping` `object`

Contém as informações de entrega do pedido.

#### `shipping.address` `object`

Contém informações do endereço de entrega do pedido.

- `shipping.address.street` `string` `1-160 caracteres`: rua do endereço.
- `shipping.address.number` `string` `1-20 caracteres`: número do endereço.
- `shipping.address.complement` `string` `1-40 caracteres`: complemento do endereço.
- `shipping.address.locality` `string` `1-60 caracteres`: bairro do endereço.
- `shipping.address.city` `string` `1-90 caracteres`: cidade do endereço.
- `shipping.address.region` `string` `1-50 caracteres`: nome do estado.
- `shipping.address.region_code` `string` `2 caracteres`: código do estado no padrão ISO 3166-2.
- `shipping.address.country` `string` `1-50 caracteres`: país do endereço no padrão ISO 3166-1 alpha-3.
- `shipping.address.postal_code` `string` `8 caracteres`: CEP do endereço.

### `qr_codes` `array of object`

Contém os QR Codes vinculados a um pedido. Ao informar `amount`, o QR code será gerado automaticamente e pode ser pago com aplicativos de outras instituições.

#### `qr_codes.expiration_date` `datetime`

Data de expiração do QR Code. Por padrão, o QR Code gerado tem validade até as 23h59 do dia seguinte caso o parâmetro não seja definido na requisição.

Exemplo: `2021-08-29T20:15:59-03:00`

#### `qr_codes.amount` `object`

Contém informações do valor a ser utilizado no QR Code.

##### `qr_codes.amount.value` `int` `5 caracteres`

Valor do QR Code.

#### `qr_codes.splits` `object`

Contém informações da divisão de pagamento.

##### `qr_codes.splits.method` `enum`

Define se os valores serão informados em valores brutos ou porcentagens.
  - `FIXED`
  - `PERCENTAGE`

##### `qr_codes.splits.receivers` `array of object`

Contém a lista de recebedores participantes da divisão de pagamento.

Cada item contém:

- `qr_codes.splits.receivers.amount` `object`: informações dos valores do recebedor.
  - `qr_codes.splits.receivers.amount.value` `int` `9 caracteres`: valor destinado ao recebedor.
- `qr_codes.splits.receivers.account` `object`: informações da conta do recebedor.
  - `qr_codes.splits.receivers.account.id` `string` `41 caracteres`: identificador único da conta PagBank do recebedor.
- `qr_codes.splits.receivers.configurations` `object`: configurações do recebedor.
  - `qr_codes.splits.receivers.configurations.custody` `object`
    - `qr_codes.splits.receivers.configurations.custody.apply` `boolean`: define se a transação terá custódia.
  - `qr_codes.splits.receivers.configurations.chargeback` `object`
    - `qr_codes.splits.receivers.configurations.chargeback.charge_transfer` `object`
      - `qr_codes.splits.receivers.configurations.chargeback.charge_transfer.percentage` `number`: porcentagem referente ao valor do chargeback que deve ser repassado ao recebedor.
- `qr_codes.splits.receivers.reason` `string` `255 caracteres`: descrição opcional para cada recebedor.

### `notification_urls` `array of object`

Contém a URL que receberá as notificações relacionadas a todas as alterações na cobrança. Este campo aceita apenas uma URL.

### `charges` `array of object`

Representa todos os dados disponíveis em uma cobrança, ou seja, na iniciação de um pagamento.

#### `charges[].id` `string` `41 caracteres`

Identificador da cobrança PagBank.

Exemplo: `CHAR_67FC568B-00D8-431D-B2E7-755E3E6C66A0`

#### `charges[].status` `string` `1-64 caracteres`

Status da cobrança.

Valores:

- `AUTHORIZED`
- `PAID`
- `IN_ANALYSIS`
- `DECLINED`
- `CANCELED`
- `WAITING`

#### `charges[].created_at` `datetime`

Data e horário em que a cobrança foi criada.

Exemplo: `2023-02-08T15:15:11.881-03:00`

#### `charges[].paid_at` `datetime`

Data e horário em que a cobrança foi paga.

Exemplo: `2023-02-08T15:15:12.000-03:00`

#### `charges[].reference_id` `string` `1-64 caracteres`

Identificador único atribuído para a cobrança.

#### `charges[].description` `string` `1-64 caracteres`

Descrição da cobrança.

#### `charges[].amount` `object`

Contém as informações do valor a ser cobrado.

- `charges[].amount.value` `int` `9 caracteres`: valor a ser cobrado em centavos.
- `charges[].amount.currency` `string` `3 caracteres`: código de moeda ISO.
- `charges[].amount.summary` `object`
  - `charges[].amount.summary.total` `int` `9 caracteres`: valor total da cobrança.
  - `charges[].amount.summary.paid` `int` `9 caracteres`: valor que foi pago.
  - `charges[].amount.summary.refunded` `int` `9 caracteres`: valor devolvido da cobrança.

#### `charges[].payment_response` `object`

Contém informações de resposta do provedor de pagamento.

- `charges[].payment_response.code` `int` `5 caracteres`: código PagBank da resposta de autorização.
- `charges[].payment_response.message` `string` `5-100 caracteres`: mensagem amigável.
- `charges[].payment_response.reference` `string` `4-20 caracteres`: NSU da autorização.
- `charges[].payment_response.brand_reference_id` `string` `15 caracteres`: NRID retornado pela bandeira Elo.

#### `charges[].payment_method` `object`

Contém as informações do método de pagamento da cobrança.

- `charges[].payment_method.type` `enum`: método de pagamento usado na cobrança.
  - `CREDIT_CARD`
  - `DEBIT_CARD`
  - `BOLETO`
  - `PIX`
- `charges[].payment_method.installments` `int` `2 caracteres`: quantidade de parcelas.
- `charges[].payment_method.capture` `boolean`: indica se a transação de cartão de crédito deve ser capturada automaticamente.
- `charges[].payment_method.capture_before` `datetime`: data limite para captura.
- `charges[].payment_method.soft_descriptor` `string` `1-22 caracteres`: nome exibido na fatura.

#### `charges[].payment_method.card` `object`

Contém os dados de cartão de crédito, débito e token de bandeira.

- `charges[].payment_method.card.id` `string` `41 caracteres`: identificador do cartão tokenizado.
- `charges[].payment_method.card.number` `string` `14-19 caracteres`: número do cartão.
- `charges[].payment_method.card.network_token` `string` `14-19 caracteres`: número do token de bandeira.
- `charges[].payment_method.card.exp_month` `int` `1/2 caracteres`: mês de expiração.
- `charges[].payment_method.card.exp_year` `int` `3/4 caracteres`: ano de expiração.
- `charges[].payment_method.card.security_code` `string` `3/4 caracteres`: código de segurança.
- `charges[].payment_method.card.store` `boolean`: indica se o cartão será armazenado.
- `charges[].payment_method.card.brand` `string` `20 caracteres`: bandeira do cartão.
- `charges[].payment_method.card.product` `string` `20 caracteres`: retornado quando o cartão for do tipo `PRE_PAID`.
- `charges[].payment_method.card.first_digits` `int` `6 caracteres`: BIN do cartão.
- `charges[].payment_method.card.last_digits` `int` `4 caracteres`: últimos dígitos do cartão.

#### `charges[].payment_method.card.holder` `object`

Contém as informações do portador do cartão.

- `charges[].payment_method.card.holder.name` `string` `1-30 caracteres`: nome do portador.
- `charges[].payment_method.card.holder.tax_id` `string` `11/14 caracteres`: CPF ou CNPJ do portador.

#### `charges[].payment_method.token_data` `object`

Dados adicionais de tokenização de rede.

- `charges[].payment_method.token_data.requestor_id` `string` `11 caracteres`: identificador de quem gerou o token.
- `charges[].payment_method.token_data.wallet` `enum`: tipo de carteira que armazenou o token.
  - `APPLE_PAY`
  - `GOOGLE_PAY`
  - `SAMSUNG_PAY`
  - `MERCHANT_TOKENIZATION_PROGRAM`
- `charges[].payment_method.token_data.cryptogram` `string` `40 caracteres`: criptograma gerado pela bandeira.
- `charges[].payment_method.token_data.ecommerce_domain` `string` `150 caracteres`: domínio de origem da transação.
- `charges[].payment_method.token_data.assurance_level` `int` `2 caracteres`: nível de confiança do token.

#### `charges[].payment_method.authentication_method` `object`

Dados adicionais de autenticação vinculados à transação.

- `charges[].payment_method.authentication_method.type` `enum`
  - `THREEDS`
  - `INAPP`
- `charges[].payment_method.authentication_method.cavv` `string` `80 caracteres`
- `charges[].payment_method.authentication_method.eci` `string` `2 caracteres`
- `charges[].payment_method.authentication_method.xid` `string` `80 caracteres`
- `charges[].payment_method.authentication_method.version` `string` `10 caracteres`
- `charges[].payment_method.authentication_method.dstrans_id` `string` `80 caracteres`
- `charges[].payment_method.authentication_method.status` `string` `80 caracteres`

#### `charges[].payment_method.payment_instructions` `object`

Contém instruções de pagamento, incluindo regras de multa, juros e descontos.

- `charges[].payment_method.payment_instructions.fine` `object`
  - `charges[].payment_method.payment_instructions.fine.date` `string` `10 caracteres`
  - `charges[].payment_method.payment_instructions.fine.value` `int` `9 caracteres`
- `charges[].payment_method.payment_instructions.interest` `object`
  - `charges[].payment_method.payment_instructions.interest.date` `string` `10 caracteres`
  - `charges[].payment_method.payment_instructions.interest.value` `int` `9 caracteres`
- `charges[].payment_method.payment_instructions.discounts` `object`
  - `charges[].payment_method.payment_instructions.discounts.date` `string` `10 caracteres`
  - `charges[].payment_method.payment_instructions.discounts.value` `int` `9 caracteres`

#### `charges[].payment_method.boleto` `object`

Contém os dados para geração do boleto.

- `charges[].payment_method.boleto.due_date` `string` `10 caracteres`
- `charges[].payment_method.boleto.template` `string` `10 caracteres`
- `charges[].payment_method.boleto.days_until_expiration` `string` `10 caracteres`
- `charges[].payment_method.boleto.instruction_lines` `object`
  - `charges[].payment_method.boleto.instruction_lines.line_1` `string` `1-75 caracteres`
  - `charges[].payment_method.boleto.instruction_lines.line_2` `string` `1-75 caracteres`
- `charges[].payment_method.boleto.holder` `object`
  - `charges[].payment_method.boleto.holder.name` `string` `1-30 caracteres`
  - `charges[].payment_method.boleto.holder.tax_id` `string` `11/14 caracteres`
  - `charges[].payment_method.boleto.holder.email` `string` `10-255 caracteres`
  - `charges[].payment_method.boleto.holder.address` `object`
    - `charges[].payment_method.boleto.holder.address.street` `string` `1-160 caracteres`
    - `charges[].payment_method.boleto.holder.address.number` `string` `1-20 caracteres`
    - `charges[].payment_method.boleto.holder.address.complement` `string` `1-40 caracteres`
    - `charges[].payment_method.boleto.holder.address.locality` `string` `1-60 caracteres`
    - `charges[].payment_method.boleto.holder.address.city` `string` `1-90 caracteres`
    - `charges[].payment_method.boleto.holder.address.region` `string` `1-50 caracteres`
    - `charges[].payment_method.boleto.holder.address.region_code` `string` `2 caracteres`
    - `charges[].payment_method.boleto.holder.address.country` `string` `1-50 caracteres`
    - `charges[].payment_method.boleto.holder.address.postal_code` `string` `8 caracteres`

#### `charges[].payment_method.pix` `object`

Contém os dados do pagamento Pix.

- `charges[].payment_method.pix.end_to_end_id` `string`: id fim a fim da transação.
- `charges[].payment_method.pix.holder` `object`
  - `charges[].payment_method.pix.holder.name` `string`
  - `charges[].payment_method.pix.holder.tax_id` `string`

### `recurring` `object`

Contém as informações da recorrência.

- `recurring.type` `enum`
  - `INITIAL`
  - `SUBSEQUENT`
  - `UNSCHEDULED`
  - `STANDING ORDER`
- `recurring.recurrence_id` `string` `15 caracteres`
- `recurring.original_amount` `int` `10 caracteres`

### `sub_merchant` `object`

Contém os dados do sub lojista, usado por sub-adquirentes para transações com Cartão de Crédito.

- `sub_merchant.reference_id` `string` `15 caracteres`
- `sub_merchant.name` `string` `60 caracteres`
- `sub_merchant.tax_id` `string` `11 ou 14 caracteres`
- `sub_merchant.mcc` `string` `4 caracteres`
- `sub_merchant.address` `object`
  - `sub_merchant.address.street` `string` `1-160 caracteres`
  - `sub_merchant.address.number` `string` `1-20 caracteres`
  - `sub_merchant.address.complement` `string` `1-40 caracteres`
  - `sub_merchant.address.locality` `string` `1-60 caracteres`
  - `sub_merchant.address.city` `string` `1-90 caracteres`
  - `sub_merchant.address.region` `string` `1-50 caracteres`
  - `sub_merchant.address.region_code` `string` `2 caracteres`
  - `sub_merchant.address.country` `string` `1-50 caracteres`
  - `sub_merchant.address.postal_code` `string` `8 caracteres`
- `sub_merchant.phone` `object`
  - `sub_merchant.phone.country` `string` `3 caracteres`
  - `sub_merchant.phone.area` `string` `2 caracteres`
  - `sub_merchant.phone.number` `string` `8-9 caracteres`
  - `sub_merchant.phone.type` `string` `enum`
    - `MOBILE`
    - `BUSINESS`
    - `HOME`

### `metadata` `map`

Conjunto de pares chave-valor que podem ser anexados ao objeto.

### `links` `array of object`

Contém as informações de links relacionadas ao recurso.

Cada item contém:

- `rel` `enum`: tipo do relacionamento ao recurso.
- `href` `string` `5-2048 caracteres`: endereço HTTP do recurso.
- `media` `string` `11-64 caracteres`: tipo de mídia.
- `type` `enum`: método HTTP em uso.
  - `GET`
  - `POST`
  - `DELETE`
  - `PUT`
