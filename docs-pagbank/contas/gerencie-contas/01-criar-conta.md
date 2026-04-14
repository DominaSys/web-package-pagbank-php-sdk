# Criar conta

`POST https://sandbox.api.pagseguro.com/accounts`

Este endpoint permite que você crie uma conta. Consulte o [objeto Conta](../objeto-conta.md) em caso de dúvida sobre os parâmetros utilizados nesse endpoint.

> Acesse o guia do serviço de [cadastro de clientes](../../cadastro-de-clientes.md) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Headers

### `x-client-id` `string`

Identificador único fornecido no momento da criação da aplicação.

### `x-client-secret` `string`

Chave privada fornecida no momento da criação da aplicação.

### `Authorization` `string`

Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Body Params

### `type` `string` `required`

Tipo de conta a ser criada.

Valores permitidos:

- `BUYER`
- `SELLER`
- `ENTERPRISE`

### `business_category` `string`

Classificação do negócio. Deve ser informada para todas as contas do tipo `SELLER` ou `ENTERPRISE`.

Valores permitidos:

- `ADULT_CONTENT`
- `ARCHITECTURAL_AND_ENGINEERING`
- `ART_AND_ANTIQUE`
- `BAKERY`
- `BEAUTY_AND_BARBER`
- `BROKER`
- `BUTCHERY`
- `CLOTHING_AND_ACCESSORIES`
- `DENTISTRY`
- `DOOR_TO_DOOR_SALES`
- `EDUCATION`
- `ENTERTAINMENT`
- `FLORIST`
- `FREIGHT_FORWARDER`
- `GENERAL_CONTRACTOR`
- `GOVERNMENT`
- `HEALTH_AND_BEAUTY_SERVICE`
- `HOUSEHOLD_APPLIANCE`
- `HOUSE_HOLD`
- `JEWELRY_AND_WATCH`
- `KEY_CHAIN`
- `LEGAL_SERVICE`
- `LODGING`
- `MEDICAL_SERVICE`
- `NONDURABLE_GOODS`
- `OTHER_SERVICES`
- `PACKAGE_STORE`
- `PERSONAL_TRAINER`
- `PET_SUPPLIES`
- `PHOTOGRAPHY_AND_VIDEO`
- `PODIATRIST_AND_CHIROPODIST`
- `PROFESSIONAL_SERVICE`
- `REAL_ESTATE_AGENT`
- `RELIGION_AND_SPIRITUALITY`
- `REPAIR_SHOPS`
- `RESTAURANT`
- `SHOE_STORE`
- `SUPERMARKET`
- `TAILOR_AND_SEAMSTRESS`
- `TATTOOIST`
- `TELECOMMUNICATION`
- `TRANSPORTATION_SERVICE`
- `VEHICLE_AND_PARTS`
- `VEHICLE_SERVICES`
- `VETERINARY_SERVICE`

### `email` `string` `required`

E-mail utilizado no login da conta.

### `person` `object` `required`

Dados do dono da conta ou de um sócio em caso de contas para empresas. Apenas um sócio deverá ser informado para esse caso.

#### `name` `string` `required`

Nome completo do usuário ou do sócio.

#### `birth_date` `string` `required`

Data de nascimento do usuário ou do sócio. Formato: `yyyy-mm-dd`.

#### `mother_name` `string` `required`

Nome da mãe do dono da conta ou do sócio.

#### `tax_id` `string` `required`

CPF do dono da conta ou do único sócio.

#### `address` `object` `nullable`

Objeto contendo as informações do endereço.

- `street` `string` `required`: rua do endereço.
- `number` `string` `required`: número do endereço.
- `complement` `string` `nullable`: complemento do endereço.
- `locality` `string` `required`: bairro do endereço.
- `city` `string` `required`: cidade do endereço.
- `region_code` `string` `required`: código do estado do endereço no padrão ISO 3166-2.
- `country` `string` `required`: país do endereço no padrão ISO 3166-1 alpha-3.
- `postal_code` `string` `required`: CEP do endereço.

#### `phones` `array of objects` `nullable`

Contém uma lista de telefones de contato.

Cada item contém:

- `country` `string` `required`: código de operadora do país (DDI).
- `area` `string` `required`: código de operadora local (DDD).
- `number` `string` `required`: número de telefone.

### `company` `object` `nullable`

Dados de cadastro da empresa em caso de contas para pessoas jurídicas (`SELLER`).

Obrigatório caso você selecione `type = SELLER`.

#### `name` `string` `nullable`

Razão Social da empresa.

#### `tax_id` `string` `nullable`

CNPJ da empresa.

#### `address` `object` `nullable`

Objeto contendo as informações do endereço da empresa.

- `street` `string` `required`: rua do endereço.
- `number` `string` `required`: número do endereço.
- `complement` `string` `nullable`: complemento do endereço.
- `locality` `string` `required`: bairro do endereço.
- `city` `string` `required`: cidade do endereço.
- `region_code` `string` `required`: código do estado do endereço no padrão ISO 3166-2.
- `country` `string` `required`: país do endereço no padrão ISO 3166-1 alpha-3.
- `postal_code` `string` `required`: CEP do endereço.

#### `phones` `array of objects` `nullable`

Objeto contendo uma lista de telefones da empresa.

Cada item contém:

- `country` `string` `required`: código de operadora do país (DDI).
- `area` `string` `required`: código de operadora local (DDD).
- `number` `string` `required`: número de telefone.

### `tos_acceptance` `object` `required`

Aceite dos termos de uso do dono da conta na plataforma do parceiro.

#### `user_ip` `string` `nullable`

IP que identifica o dispositivo em que o dono da conta concordou com os termos de aceite na plataforma do parceiro.

#### `date` `string` `nullable`

Momento em que o usuário concordou com os termos de aceite na plataforma do parceiro.

## Responses

### `201`

Criação realizada com sucesso.

#### Criar conta (BUYER)

- `id` `string`: identificador da conta criada.
- `created_at` `string`: data e hora de criação da conta.
- `token` `object`: dados de autenticação retornados na criação.
  - `token_type` `string`: tipo do token.
  - `access_token` `string`: token de acesso.
  - `expires_in` `integer` `default: 0`: tempo de expiração do token, em segundos.
  - `refresh_token` `string`: token de renovação.
  - `scope` `string`: escopo associado ao token.
- `type` `string`: tipo da conta criada.
- `email` `string`: e-mail utilizado na conta.
- `person` `object`: dados do dono da conta.
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
    - `country` `string`: país.
  - `phones` `array of objects`: telefones de contato.
    - `area` `string`: DDD.
    - `country` `string`: DDI.
    - `number` `string`: número do telefone.
- `tos_acceptance` `object`: aceite dos termos.
  - `user_ip` `string`: IP do dispositivo.
  - `date` `string`: data e hora do aceite.

#### Criar conta (SELLER - PJ)

- `id` `string`: identificador da conta criada.
- `created_at` `string`: data e hora de criação da conta.
- `token` `object`: dados de autenticação retornados na criação.
  - `token_type` `string`: tipo do token.
  - `access_token` `string`: token de acesso.
  - `expires_in` `integer` `default: 0`: tempo de expiração do token, em segundos.
  - `refresh_token` `string`: token de renovação.
  - `scope` `string`: escopo associado ao token.
- `type` `string`: tipo da conta criada.
- `email` `string`: e-mail utilizado na conta.
- `person` `object`: dados do dono da conta ou do sócio.
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
    - `country` `string`: país.
  - `phones` `array of objects`: telefones de contato.
    - `area` `string`: DDD.
    - `country` `string`: DDI.
    - `number` `string`: número do telefone.
- `company` `object`: dados cadastrais da empresa.
  - `name` `string`: razão social.
  - `tax_id` `string`: CNPJ.
  - `address` `object`: endereço cadastrado da empresa.
    - `region_code` `string`: código do estado.
    - `city` `string`: cidade.
    - `postal_code` `string`: CEP.
    - `street` `string`: rua.
    - `number` `string`: número.
    - `complement` `string`: complemento.
    - `locality` `string`: bairro.
    - `country` `string`: país.
  - `phones` `array of objects`: telefones da empresa.
    - `area` `string`: DDD.
    - `country` `string`: DDI.
    - `number` `string`: número do telefone.
- `business_category` `string`: classificação do negócio.
- `tos_acceptance` `object`: aceite dos termos.
  - `user_ip` `string`: IP do dispositivo.
  - `date` `string`: data e hora do aceite.

#### Criar conta (SELLER - PF)

- `id` `string`: identificador da conta criada.
- `created_at` `string`: data e hora de criação da conta.
- `token` `object`: dados de autenticação retornados na criação.
  - `token_type` `string`: tipo do token.
  - `access_token` `string`: token de acesso.
  - `expires_in` `integer` `default: 0`: tempo de expiração do token, em segundos.
  - `refresh_token` `string`: token de renovação.
  - `scope` `string`: escopo associado ao token.
- `type` `string`: tipo da conta criada.
- `email` `string`: e-mail utilizado na conta.
- `person` `object`: dados do dono da conta ou do sócio.
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
    - `country` `string`: país.
  - `phones` `array of objects`: telefones de contato.
    - `area` `string`: DDD.
    - `country` `string`: DDI.
    - `number` `string`: número do telefone.
- `business_category` `string`: classificação do negócio.
- `tos_acceptance` `object`: aceite dos termos.
  - `user_ip` `string`: IP do dispositivo.
  - `date` `string`: data e hora do aceite.

#### Criar conta (ENTERPRISE)

- `id` `string`: identificador da conta criada.
- `created_at` `string`: data e hora de criação da conta.
- `token` `object`: dados de autenticação retornados na criação.
  - `token_type` `string`: tipo do token.
  - `access_token` `string`: token de acesso.
  - `expires_in` `integer` `default: 0`: tempo de expiração do token, em segundos.
  - `refresh_token` `string`: token de renovação.
  - `scope` `string`: escopo associado ao token.
- `type` `string`: tipo da conta criada.
- `email` `string`: e-mail utilizado na conta.
- `person` `object`: dados do dono da conta ou do sócio.
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
    - `country` `string`: país.
  - `phones` `array of objects`: telefones de contato.
    - `area` `string`: DDD.
    - `country` `string`: DDI.
    - `number` `string`: número do telefone.
- `business_category` `string`: classificação do negócio.
- `tos_acceptance` `object`: aceite dos termos.
  - `user_ip` `string`: IP do dispositivo.
  - `date` `string`: data e hora do aceite.

Exemplos:

- Criar conta (`BUYER`)
- Criar conta (`SELLER - PJ`)
- Criar conta (`SELLER - PF`)
- Criar conta (`ENTERPRISE`)

### `400`

Requisição inválida.

#### `error_messages` `array of objects`

Lista com os erros retornados pela API.

Cada item contém:

- `code` `string`: código do erro.
- `parameter_name` `string`: nome do parâmetro relacionado ao erro.
- `description` `string`: descrição do erro.
- `errors` `array of strings`: lista com os detalhes do erro.
