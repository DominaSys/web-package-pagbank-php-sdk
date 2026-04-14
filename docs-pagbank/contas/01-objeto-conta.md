# Objeto Conta

Este objeto apresenta todos os dados disponíveis para criar uma conta.

## Atributos

### `type` `enum`

Tipo de conta a ser criada.

Valores:

- `BUYER`: comprador.
- `SELLER`: vendedor.
- `ENTERPRISE`

### `id` `string` `42 caracteres`

Identificador único da conta. Formato: `ACCO_XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX`.

Exemplo: `ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36`

### `business_category` `enum`

Classificação do negócio. Deve ser informada para todas as contas do tipo `SELLER` ou `ENTERPRISE`.

Valores:

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

### `email` `string`

E-mail utilizado no login da conta.

Exemplo: `josecarlos@hotmail.com`

### `person` `object`

Dados do proprietário da conta ou de um sócio em caso de contas para empresas. Apenas um sócio deverá ser informado para esse caso.

#### `name` `string`

Nome completo do usuário ou do sócio.

Exemplo: `José Carlos Silva`

#### `birth_date` `string`

Data de nascimento do usuário ou do sócio. Formato: `yyyy-mm-dd`.

Exemplo: `1991-10-10`

#### `mother_name` `string`

Nome da mãe do dono da conta ou do sócio.

Exemplo: `Maria Silva`

#### `tax_id` `string`

CPF do dono da conta ou do sócio.

Exemplo: `77231520000130`

#### `address` `object`

Contém informações do endereço de entrega do pedido.

- `street` `string`: rua do endereço.
- `number` `string`: número do endereço.
- `complement` `string`: complemento do endereço.
- `locality` `string`: bairro do endereço.
- `city` `string`: cidade do endereço.
- `region_code` `string`: código do estado no padrão ISO 3166-2.
- `country` `string`: país no padrão ISO 3166-1 alpha-3.
- `postal_code` `string`: CEP do endereço.

#### `phones` `array of object`

Contém informações telefônicas do proprietário ou parceiro da conta.

Cada item contém:

- `country` `string`: código de operadora do país (DDI).
- `area` `string`: código de operadora local (DDD).
- `number` `string`: número do telefone.
- `type` `string`: tipo de telefone.
  - `MOBILE`: telefone celular.
  - `LANDLINE`: telefone fixo.

### `company` `object`

Dados de cadastro da empresa em caso de contas para pessoas jurídicas (`SELLER`, `ENTERPRISE`).

#### `name` `string`

Razão Social da empresa.

Exemplo: `ACME Corporation`

#### `trade_name` `string`

Nome fantasia da empresa.

Exemplo: `American Company Making Everything`

#### `tax_id` `string`

CNPJ da empresa.

#### `address` `object`

Contém informações do endereço da empresa.

- `street` `string`: rua do endereço.
- `number` `string`: número do endereço.
- `complement` `string`: complemento do endereço.
- `locality` `string`: bairro do endereço.
- `city` `string`: cidade do endereço.
- `region_code` `string`: código do estado no padrão ISO 3166-2.
- `country` `string`: país no padrão ISO 3166-1 alpha-3.
- `postal_code` `string`: CEP do endereço.

#### `phones` `array of object`

Contém informações sobre a lista de telefones da empresa.

Cada item contém:

- `country` `string`: código de operadora do país (DDI).
- `area` `string`: código de operadora local (DDD).
- `number` `string`: número do telefone.
- `type` `string`: tipo de telefone.
  - `MOBILE`: telefone celular.
  - `LANDLINE`: telefone fixo.

### `account_advanced` `boolean`

Sinaliza se o cliente possui ou não uma conta avançada.

Exemplo: `true`

### `created_at` `datetime`

Data e horário em que a conta foi criada.

Exemplo: `2021-07-23T15:17:12.115601-03:00`

### `tos_acceptance` `object`

Aceite dos termos de aceite por parte do dono da conta na plataforma do parceiro.

#### `user_ip` `string`

IP que identifica o dispositivo em que o dono da conta concordou com os termos de aceite na plataforma do parceiro.

Exemplo: `127.0.0.1`

#### `date` `string`

Momento em que o usuário concordou com os termos de aceite na plataforma do parceiro.

Exemplo: `2019-04-17T20:07:07.002-02`

### `token` `object`

Conjunto de informações atreladas ao token gerado que relaciona a conta do parceiro e a conta criada por ele.

#### `refresh_token` `string`

Token para atualizar o `access_token`.

Exemplo: `123`

#### `token_type` `enum`

Sempre terá valor `Bearer`.

Exemplo: `Bearer`

#### `access_token` `string`

Token de acesso e autenticação.

Exemplo: `123`

#### `expires_in` `integer`

Tempo de validade do `access_token`.

Exemplo: `123456`

#### `scope` `string`

Permissões concedidas.

Para contas do tipo `BUYER`, o único escopo possível é `accounts.read`.

Para contas do tipo `SELLER`, os escopos criados são `accounts.read` e `payments.create`.

Exemplo: `accounts.read`
