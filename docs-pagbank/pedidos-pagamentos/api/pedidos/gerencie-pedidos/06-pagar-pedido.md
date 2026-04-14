# Pagar pedido

`POST /orders/{order_id}/pay`

Este endpoint permite realizar a cobrança de um pedido previamente criado por meio de um identificador do pedido PagBank.

## Path Params

### `order_id` `string` `required`

Identificador do pedido PagBank.

Formato: `ORDE_XXXXXXXXXXXX`

Esse parametro voce recebe na resposta da criacao do pedido como `id`.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Body Params

### `charges` `array of objects`

Contem as informacoes necessarias para a execucao da cobranca, definindo os dados do meio de pagamento e o valor do pedido.

#### `charges[].reference_id` `string`

Identificador unico atribuido para a cobrança.

#### `charges[].description` `string`

Descricao da cobrança.

#### `charges[].amount` `object`

Especifica a quantia da cobrança, com o valor e a moeda.

- `charges[].amount.value` `int32`: valor a ser cobrado em centavos.
- `charges[].amount.currency` `string`: codigo da moeda, normalmente `BRL`.

#### `charges[].payment_method` `object`

Contem as informacoes do metodo de pagamento da cobrança.

##### `charges[].payment_method.type` `string`

Indica o metodo de pagamento usado na cobrança.

- `CREDIT_CARD` para Cartao de Credito. O envio do objeto `card` e obrigatorio.
- `DEBIT_CARD` para Cartao de Debito. O envio do objeto `card` e obrigatorio.
- `BOLETO` para Boleto. O envio do objeto `boleto` e obrigatorio.

##### `charges[].payment_method.installments` `int32`

Quantidade de parcelas.
Obrigatorio para o metodo de pagamento Cartao de Credito.

##### `charges[].payment_method.capture` `boolean`

Parametro que indica se uma transacao de cartao de credito deve ser apenas pre-autorizada, reservando o valor da cobranca no cartao do cliente de 6 ate 29 dias, ou se a transacao deve ser capturada automaticamente, realizando a cobrança em apenas um passo.

Obrigatorio para o metodo de pagamento cartao de credito.
Funcao indisponivel para o metodo de pagamento cartao de debito e token de bandeira (credito).

- `VISA`, `MASTERCARD` e `ELO` permitem captura com cartao de credito e token de bandeira em ate 29 dias.
- `AMEX` e `HIPERCARD` permitem captura apenas com cartao de credito e em ate 6 dias.
- Informar `false` para pre-autorizacao.
- Informar `true` para cobrança em um passo.

##### `charges[].payment_method.soft_descriptor` `string`

Parametro responsavel pelo que sera exibido como Nome na Fatura do cliente.
Aplicavel no momento apenas para Cartao de credito.
Nao permite caracteres especiais. Acentuacoes serao substituidas por caracteres sem acentos e os demais caracteres especiais serao removidos.

##### `charges[].payment_method.card` `object`

Objeto contendo os dados de Cartão de Crédito, Cartão de Débito ou Token de Bandeira.

##### `charges[].payment_method.card.id` `string`

Identificador PagBank do Cartao de Credito salvo. Função indisponivel para cartao de debito e token de bandeira.

##### `charges[].payment_method.card.encrypted` `string`

Criptograma do cartao criptografado.

##### `charges[].payment_method.card.number` `string`

Numero do Cartao de Credito ou Cartao de Debito.

##### `charges[].payment_method.card.network_token` `string`

Numero do Token de Bandeira.

##### `charges[].payment_method.card.exp_month` `int32`

Mes de expiracao do Cartao de Credito, Cartao de Debito ou Token de Bandeira.

##### `charges[].payment_method.card.exp_year` `int32`

Ano de expiracao do Cartao de Credito, Cartao de Debito ou Token de Bandeira.

##### `charges[].payment_method.card.security_code` `string`

Codigo de Seguranca do Cartao de Credito, Cartao de Debito ou Token de Bandeira.

##### `charges[].payment_method.card.store` `boolean`

Indica se o cartao devera ser armazenado no PagBank para futuras compras.
Funcao indisponivel para Cartao de Debito e Token de Bandeira.
- `false` ou omitir o parametro nao armazena o cartao.
- `true` armazena o cartao.
- Na resposta, o token do cartao sera retornado em `payment_method.card.id`.

###### `charges[].payment_method.card.holder` `object`

Contem as informacoes do portador do Cartão de Crédito, Cartão de Débito ou Token de Bandeira.

###### `charges[].payment_method.card.holder.name` `string`

Nome do portador do Cartao de Credito, Cartao de Debito e Token de Bandeira.
Obrigatorio para cobrancas com 3DS e Criptografia.

###### `charges[].payment_method.card.holder.tax_id` `string`

Documento de identificacao do portador do Cartao de Credito, Cartao de Debito e Token de Bandeira.

###### `charges[].payment_method.card.token_data` `object`

Objeto contendo os dados adicionais de Tokenização de Bandeira.

###### `charges[].payment_method.card.token_data.requestor_id` `string`

Identificador de quem gerou o Token de Bandeira.

###### `charges[].payment_method.card.token_data.wallet` `string`

Tipo de carteira que armazenou o Token de Bandeira.

###### `charges[].payment_method.card.token_data.cryptogram` `string`

Criptograma gerado pela bandeira.

###### `charges[].payment_method.card.token_data.ecommerce_domain` `string`

Identificador do dominio de origem da transacao, normalmente em formato de dominio reverso.

###### `charges[].payment_method.card.token_data.assurance_level` `int32`

Conteudo que indica o nivel de confianca do Token de Bandeira.

##### `charges[].payment_method.authentication_method` `object`

Objeto contendo os dados adicionais de autenticacao vinculados a uma transacao.

##### `charges[].payment_method.authentication_method.type` `string`

Indica o metodo de autenticacao utilizado na cobrança.
Condicional para Token de Bandeira ELO.
- `THREEDS` para autenticacao 3DS.
- `INAPP` para autenticacao InApp.

##### `charges[].payment_method.authentication_method.id` `string`

Identificador do metodo de autenticacao utilizado.

##### `charges[].payment_method.authentication_method.cavv` `string`

Identificador unico gerado em cenario de sucesso de autenticacao do cliente.

##### `charges[].payment_method.authentication_method.eci` `string`

Indicador E-Commerce retornado quando ocorre uma autenticacao.

##### `charges[].payment_method.authentication_method.xid` `string`

Identificador de uma transacao de um MPI.
Recomendado para a bandeira VISA.
Condicional para 3DS.

##### `charges[].payment_method.authentication_method.version` `string`

Versao do protocolo 3DS utilizado na autenticacao.

##### `charges[].payment_method.authentication_method.dstrans_id` `string`

ID da transacao gerada pelo servidor de diretorio durante uma autenticacao.
Recomendado para a bandeira MASTERCARD.
Condicional para 3DS.

#### `charges[].payment_method.boleto` `object`

Objeto contendo os dados para geracao do boleto.

##### `charges[].payment_method.boleto.due_date` `string`

Data de vencimento do Boleto.

##### `charges[].payment_method.boleto.instruction_lines.line_1` `string`

Primeira linha de instrucoes sobre o pagamento do Boleto.

##### `charges[].payment_method.boleto.instruction_lines.line_2` `string`

Segunda linha de instrucoes sobre o pagamento do Boleto.

##### `charges[].payment_method.boleto.holder.name` `string`

Nome do responsavel pelo pagamento do Boleto.

##### `charges[].payment_method.boleto.holder.tax_id` `string`

Numero do documento do responsavel pelo pagamento do Boleto.

##### `charges[].payment_method.boleto.holder.email` `string`

Email do responsavel pelo pagamento do Boleto.

##### `charges[].payment_method.boleto.holder.address.street` `string`

Rua do endereco.

##### `charges[].payment_method.boleto.holder.address.number` `string`

Numero do endereco.

##### `charges[].payment_method.boleto.holder.address.locality` `string`

Bairro do endereco.

##### `charges[].payment_method.boleto.holder.address.city` `string`

Cidade do endereco.

##### `charges[].payment_method.boleto.holder.address.region` `string`

Estado do endereco.

##### `charges[].payment_method.boleto.holder.address.region_code` `string`

Codigo do Estado do endereco, no padrao ISO 3166-2.

##### `charges[].payment_method.boleto.holder.address.country` `string`

Pais do endereco, no padrao ISO 3166-1 alpha-3.

##### `charges[].payment_method.boleto.holder.address.postal_code` `string`

CEP do endereco.

#### `charges[].notification_urls` `string`

URLs que serao notificadas em toda alteracao ocorrida na cobrança.
Necessario que seja em ambiente seguro com SSL (HTTPS).

#### `charges[].splits` `object`

Objeto contendo as contas e os valores a serem pagos para transacoes com divisao da cobranca.

##### `charges[].splits.method` `string`

Especifica como a divisao do pagamento sera realizada.
- `FIXED`: informe os valores monetarios.
- `PERCENTAGE`: informe o percentual do montante total.

##### `charges[].splits.receivers` `array of objects`

Especifica as contas que receberao partes distintas do pagamento e define os valores ou percentuais a serem transferidos para cada uma delas.

##### `charges[].splits.receivers[].id` `string`

Identificador unico da conta do recebedor.

##### `charges[].splits.receivers[].value` `int32`

Define o valor ou percentual a ser atribuido a cada recebedor.
- Se `FIXED`, informe os valores em centavos, garantindo que a soma seja igual ao total da transacao.
- Se `PERCENTAGE`, informe valores inteiros sem casas decimais, garantindo que a soma totalize 100%.

## Responses

### `201`

Criacao da cobranca realizada com sucesso.

### `400`

Requisicao invalida.
