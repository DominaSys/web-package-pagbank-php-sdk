# Criar chave pública

`POST /public-keys`

Este endpoint permite que você crie uma chave pública. Chaves públicas são utilizadas para criptografia de cartões e autenticação 3DS.

No corpo da requisição é necessário informar o tipo do recurso desejado. Por hora, apenas o tipo `card` está disponível.

> Acesse o guia do serviço de [chaves públicas](/docs/chaves-publicas) para mais informações sobre seu funcionamento e funcionalidades disponíveis.

## Body Params

| Parâmetro | Tipo | Obrigatório | Descrição | Valores |
| --- | --- | --- | --- | --- |
| `type` | string | Sim | Define o tipo de recurso desejado. | `card` |

## Headers

| Header | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `Authorization` | string | Sim | Token de autenticação. Deve ser enviado no formato `Bearer <token>`. |

## Responses

| Status | Descrição |
| --- | --- |
| `201` | Criado com sucesso. |
| `400` | Requisição inválida. |
