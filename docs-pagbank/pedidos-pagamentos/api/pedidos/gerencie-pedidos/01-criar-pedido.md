# Criar pedido

`POST /orders`

Este endpoint permite criar um pedido e, dependendo do meio de pagamento, concluir o pagamento no mesmo fluxo.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.
- `x-idempotency-key` `string`: chave de idempotencia.
- `accept` `string`: `application/json` ou `text/plain`.

## Body Params

### `reference_id` `string`

Identificador unico atribuido para o pedido.

Exemplo: `ex-00001`

### `customer` `object`

Objeto contendo as informacoes do comprador.

#### `customer.name` `string`

Nome do cliente, com nome e sobrenome.

Exemplo: `Jose da Silva`

#### `customer.email` `string`

E-mail do cliente.

Exemplo: `email@test.com`

#### `customer.tax_id` `string`

Documento de identificacao do cliente.

Obrigatorio.

#### `customer.phones` `array of object`

Contem uma lista de telefones do cliente.

Cada item contem:

- `customer.phones.country` `string`: codigo de operadora do pais (DDI).
- `customer.phones.area` `string`: codigo de operadora local (DDD).
- `customer.phones.number` `string`: numero do telefone.

### `items` `array of objects`

Contem as informacoes dos itens inseridos no pedido.

Cada item contem:

- `items.name` `string`: nome dado ao item.
- `items.quantity` `int32`: quantidade referente ao item.
- `items.unit_amount` `int32`: valor unitario do item.

### `shipping` `object`

Contem as informacoes de entrega do pedido.

#### `shipping.address` `object`

Contem informacoes do endereco de entrega do pedido.

- `shipping.address.street` `string`: rua do endereco.
- `shipping.address.number` `string`: numero do endereco.
- `shipping.address.complement` `string`: complemento do endereco.
- `shipping.address.locality` `string`: bairro do endereco.
- `shipping.address.city` `string`: cidade do endereco.
- `shipping.address.region_code` `string`: codigo do estado.
- `shipping.address.country` `string`: pais do endereco.
- `shipping.address.postal_code` `string`: CEP do endereco.

### `qr_codes` `array of objects`

Objeto contendo informacao do valor do pedido para pagar com QR Code exclusivo do PagBank.

#### `qr_codes.amount` `object`

Objeto contendo as informacoes do valor a ser utilizado no QR Code.

##### `qr_codes.amount.value` `int32`

Valor do QR Code.

Exemplo: `500`

#### `qr_codes.expiration_date` `string`

Data de expiracao do QR Code.

Exemplo: `2021-08-29T20:15:59-03:00`

### `notification_urls` `array of strings`

URLs que serao notificadas em toda alteracao ocorrida na cobranca.

### `charges` `array of objects`

Contem as informacoes necessarias para a execucao da cobranca.

#### `charges[].reference_id` `string`

Identificador unico atribuido para a cobranca.

Exemplo: `ex-00001`

#### `charges[].description` `string`

Descricao da cobranca.

#### `charges[].amount` `object`

Especifica a quantia da cobranca, com o valor e a moeda.

- `charges[].amount.value` `int32`: valor a ser cobrado em centavos.
- `charges[].amount.currency` `string`: codigo da moeda, normalmente `BRL`.

#### `charges[].payment_method` `object`

Contem as informacoes do metodo de pagamento da cobranca.

- `charges[].payment_method.type` `string`: `CREDIT_CARD`, `DEBIT_CARD` ou `BOLETO`.
- `charges[].payment_method.installments` `int32`: quantidade de parcelas.
- `charges[].payment_method.capture` `boolean`: indica se a transacao deve ser capturada automaticamente.
- `charges[].payment_method.capture_before` `string`: data limite para captura.
- `charges[].payment_method.soft_descriptor` `string`: nome exibido na fatura.

##### `charges[].payment_method.card` `object`

Objeto contendo os dados de Cartao de Credito, Cartao de Debito ou Token de Bandeira.

- `charges[].payment_method.card.id` `string`: identificador PagBank do cartao salvo.
- `charges[].payment_method.card.encrypted` `string`: criptograma do cartao criptografado.
- `charges[].payment_method.card.number` `string`: numero do cartao.
- `charges[].payment_method.card.network_token` `string`: numero do Token de Bandeira.
- `charges[].payment_method.card.exp_month` `int32`: mes de expiracao.
- `charges[].payment_method.card.exp_year` `int32`: ano de expiracao.
- `charges[].payment_method.card.security_code` `string`: codigo de seguranca.
- `charges[].payment_method.card.store` `boolean`: indica se o cartao sera armazenado.

###### `charges[].payment_method.card.holder` `object`

Contem as informacoes do portador do cartao.

- `charges[].payment_method.card.holder.name` `string`: nome do portador.
- `charges[].payment_method.card.holder.tax_id` `string`: CPF ou CNPJ do portador.

###### `charges[].payment_method.card.token_data` `object`

Objeto contendo os dados adicionais de tokenizacao de bandeira.

- `charges[].payment_method.card.token_data.requestor_id` `string`
- `charges[].payment_method.card.token_data.wallet` `string`
- `charges[].payment_method.card.token_data.cryptogram` `string`
- `charges[].payment_method.card.token_data.ecommerce_domain` `string`
- `charges[].payment_method.card.token_data.assurance_level` `int32`

###### `charges[].payment_method.authentication_method` `object`

Objeto contendo os dados adicionais de autenticacao vinculados a uma transacao.

- `charges[].payment_method.authentication_method.type` `string`
- `charges[].payment_method.authentication_method.id` `string`
- `charges[].payment_method.authentication_method.cavv` `string`
- `charges[].payment_method.authentication_method.eci` `string`
- `charges[].payment_method.authentication_method.xid` `string`
- `charges[].payment_method.authentication_method.version` `string`
- `charges[].payment_method.authentication_method.dstrans_id` `string`

##### `charges[].payment_method.boleto` `object`

Objeto contendo os dados para geracao do boleto.

- `charges[].payment_method.boleto.due_date` `string`
- `charges[].payment_method.boleto.instruction_lines.line_1` `string`
- `charges[].payment_method.boleto.instruction_lines.line_2` `string`
- `charges[].payment_method.boleto.holder.name` `string`
- `charges[].payment_method.boleto.holder.tax_id` `string`
- `charges[].payment_method.boleto.holder.email` `string`
- `charges[].payment_method.boleto.holder.address.street` `string`
- `charges[].payment_method.boleto.holder.address.number` `string`
- `charges[].payment_method.boleto.holder.address.complement` `string`
- `charges[].payment_method.boleto.holder.address.locality` `string`
- `charges[].payment_method.boleto.holder.address.city` `string`
- `charges[].payment_method.boleto.holder.address.region` `string`
- `charges[].payment_method.boleto.holder.address.region_code` `string`
- `charges[].payment_method.boleto.holder.address.country` `string`
- `charges[].payment_method.boleto.holder.address.postal_code` `string`

#### `charges[].splits` `object`

Objeto contendo as contas e os valores a serem pagos para transacoes com divisao da cobranca.

##### `charges[].splits.method` `string`

Especifica como a divisao da cobranca sera realizada.

Valores:

- `FIXED`
- `PERCENTAGE`

##### `charges[].splits.receivers` `array of objects`

Especifica as contas que receberao partes distintas do pagamento e define os valores ou percentuais a serem transferidos para cada uma delas.

Cada item contem:

- `charges[].splits.receivers.id` `string`
- `charges[].splits.receivers.value` `int32`

## Responses

### `201`

Variantes de resposta de sucesso:

- `Criar Pedido`
- `Criar/Pag Cartão`
- `Criar/Pag Token Band VISA/MASTER CRED`
- `Criar/Pag Token Band VISA/MASTER DEB`
- `Criar/Pag Token Band ELO CRED`
- `Criar/Pag Token Band ELO DEB`
- `Criar/Pag Token PagBank`
- `Criar/Pag INITIAL - Indicação de Recorrência`
- `Criar/Pag SUBSEQUENT - Indicação de Recorrência`
- `Criar/Pag 3DS - Validação Externa CRED`
- `Criar/Pag 3DS - Validação Externa DEB`
- `Criar/Pag 3DS - Validação Interna`
- `Criar Pedido - 3DS Interna`
- `Criar/Pag Cartão - PCI`
- `Criar/Pag Pedido - Boleto`
- `Criar Pedido - QR Code - PIX`
- `Pedido - Facilitadores de Pagamento`

### `400`

Erro de validacao/requisicao invalida.

Consulte [`contas/codigos-erro.md`](../../contas/codigos-erro.md) para o padrao de erros da API.
