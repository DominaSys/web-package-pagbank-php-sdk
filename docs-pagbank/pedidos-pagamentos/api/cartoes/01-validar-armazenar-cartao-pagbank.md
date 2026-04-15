# Validar e armazenar um cartao no PagBank

`POST /tokens/cards`

Valida e armazena um cartao no sistema do PagBank.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Body Params

- `encrypted` `string`: Dados do cartão criptografados. Obrigatório quando o integrador NÃO possuir certificação PCI. Caso este parâmetro for preenchido, nenhum outro parâmetro deverá ser enviado.
- `number` `string`: Número do Cartão de Crédito. Aplicável apenas quando o integrador possuir certificação PCI.
- `exp_month` `string`: Mês de expiração do Cartão de Crédito. Aplicável apenas quando o integrador possuir certificação PCI.
- `exp_year` `string`: Ano de expiração do Cartão de Crédito. Aplicável apenas quando o integrador possuir certificação PCI.
- `security_code` `string`: Código de Segurança do Cartão de Crédito. Aplicável apenas quando o integrador possuir certificação PCI.
- `holder` `object`: Portador do cartão de crédito. Aplicável apenas quando o integrador possuir certificação PCI. Caso o parâmetro `encrypted` estiver preenchido, este parâmetro poderá ser enviado de forma opcional.
  - `name` `string`: Nome do portador do Cartão de Crédito, Cartão de Débito e Token de Bandeira. Obrigatório para cobranças com 3DS e Criptografia.
  - `tax_id` `string`: Documento de identificação (CPF) do portador do Cartão de Crédito, Cartão de Débito e Token de Bandeira.

## Responses

- `200`
- `400`
