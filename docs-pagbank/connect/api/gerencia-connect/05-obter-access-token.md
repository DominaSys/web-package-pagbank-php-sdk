# Obter access token

`POST /oauth2/token`

Este endpoint permite trocar o código de autorização (`sms_code` ou `code`) por um `access_token` quando o usuário conceder a permissão. Após a troca, você poderá realizar requisições em nome do usuário.

> Acesse o guia do serviço de [Connect](/docs/connect) para mais informações sobre o funcionamento e as funcionalidades disponíveis.
>
> Se você deseja adicionar autenticação em dois fatores, acesse o guia do [Connect challenge](/docs/connect-challenge).

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.
- `X_CLIENT_ID` `string`: Identificador único fornecido no momento da criação da aplicação.
- `X_CLIENT_SECRET` `string`: Chave privada da aplicação fornecida no momento da criação.

## Body Params

- `grant_type` `string`: Indica o tipo de autenticação utilizada. Valores aceitos: `sms`, `authorization_code` e `challenge`. Obrigatório.
- `code` `string`: Código enviado para a URL no momento da permissão do usuário via Connect Authorization. Obrigatório quando `grant_type = authorization_code`.
- `sms_code` `string`: Código SMS enviado para o número cadastrado na base PagSeguro via Connect SMS. Obrigatório quando `grant_type = sms`.
- `redirect_uri` `string`: URL de redirecionamento do cliente. Deve ser a mesma do momento da permissão. Obrigatório quando `grant_type = authorization_code`.
- `email` `string`: E-mail do cliente PagBank que deseja conceder permissão para o parceiro efetuar ações em nome dele na plataforma PagSeguro. Obrigatório quando `grant_type = sms`.
- `scope` `string`: Nome do escopo. Quando `grant_type = challenge`, use obrigatoriamente `certificate.create` para solicitar a criação de um certificado digital.
- `authorization_id` `string`: Identifica a autorização obtida ao solicitar a autorização via SMS.

## Responses

- `200`

