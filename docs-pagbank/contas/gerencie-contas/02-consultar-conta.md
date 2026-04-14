# Consultar conta

`GET https://sandbox.api.pagseguro.com/accounts/{account_id}`

Este endpoint permite consultar uma conta por meio de um `account_id`, gerado no momento da criação da conta. Além disso, é necessário informar o `accessToken`, obtido através da API Connect.

> Acesse o guia do serviço de [cadastro de clientes](../../cadastro-de-clientes.md) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Path Params

### `account_id` `string` `required`

Identificador único da conta. Formato: `ACCO_XXXXXXXXXXXX`.

## Headers

### `x-client-token` `string`

Se refere ao `accessToken` do Seller, obtido através da API Connect.

### `Authorization` `string`

Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Responses

### `200`

Consulta realizada com sucesso.

#### Response body

Retorna um objeto com os seguintes campos:

- `id` `string`: identificador da conta.
- `type` `string`: tipo da conta.
- `status` `string`: status atual da conta.
- `created_at` `string`: data e hora de criação.
- `email` `string`: e-mail da conta.
- `business_category` `string`: categoria do negócio.
- `person` `object`: dados do proprietário ou sócio.
  - `birth_date` `string`: data de nascimento.
  - `name` `string`: nome completo.
  - `tax_id` `string`: CPF.
  - `mother_name` `string`: nome da mãe.
  - `address` `object`: endereço cadastrado.
    - `region_code` `string`: código do estado.
    - `city` `string`: cidade.
    - `postal_code` `string`: CEP.
    - `street` `string`: rua.
    - `number` `string`: número.
    - `complement` `string`: complemento.
    - `locality` `string`: bairro.
  - `phones` `array of objects`: telefones de contato.
    - `country` `string`: DDI.
    - `area` `string`: DDD.
    - `number` `string`: número do telefone.
- `company` `object`: dados da empresa, quando aplicável.
  - `name` `string`: razão social.
  - `trade_name` `string`: nome fantasia.
  - `tax_id` `string`: CNPJ.
  - `address` `object`: endereço cadastrado da empresa.
    - `region_code` `string`: código do estado.
    - `city` `string`: cidade.
    - `postal_code` `string`: CEP.
    - `street` `string`: rua.
    - `number` `string`: número.
    - `complement` `string`: complemento.
    - `locality` `string`: bairro.
  - `phones` `array of objects`: telefones da empresa.
    - `country` `string`: DDI.
    - `area` `string`: DDD.
    - `number` `string`: número do telefone.
- `account_advanced` `boolean`: indica se a conta é avançada.
- `tos_acceptance` `object`: aceite dos termos.
  - `user_ip` `string`: IP do dispositivo.
  - `date` `string`: data e hora do aceite.
- `token` `object`: dados do token associado.
  - `refresh_token` `string`: token de atualização.
  - `token_type` `string`: tipo do token.
  - `access_token` `string`: token de acesso.
  - `expires_in` `integer`: tempo de validade do token.
  - `scope` `string`: escopo concedido.

### `400`

Requisição inválida.
