# Solicitar autorização via Connect Authorization

`GET https://connect.sandbox.pagseguro.uol.com.br/oauth2/authorize`

> **Esse recurso não é acessado através de um endpoint de API**
>
> O processo de solicitação de autorização através do Connect Authorization é realizado por redirecionamento do cliente para a URL gerada. A página serve apenas para facilitar a visualização da criação dessa URL. Se você tentar requisitar a URL resultante, não receberá um resultado válido.

O parâmetro `scope` define o nível de permissão atribuído ao vendedor. Os valores possíveis são:

- `payments.read`: Permissão para visualizar pedidos e cobranças.
- `payments.create`: Permissão para criar e visualizar pedidos e cobranças.
- `payments.refund`: Permissão para fazer reembolsos.
- `accounts.read`: Permissão para consultar os dados de cadastro do vendedor.
- `payments.split.read`: Permissão para visualizar cobranças com divisão de pagamento.
- `checkout.create`: Permissão para criar Checkout PagBank.
- `checkout.view`: Permissão para visualizar Checkout PagBank.
- `checkout.update`: Permissão para atualizar checkouts PagBank criados.

Caso você deseje adicionar mais de uma permissão, combine os identificadores com um espaço entre eles, por exemplo: `payments.read payments.create`.

> Acesse o guia do [Connect Authorization](/docs/connect-authorization) para mais informações sobre os passos necessários para solicitar a autorização.

## Query Params

- `client_id` `string`: Identificador único fornecido no momento da criação da aplicação. `36` caracteres.
- `response_type` `string`: Define o tipo de resposta desejado. Use `code`.
- `redirect_uri` `string`: URL para o redirecionamento do usuário após a autorização. Deve ser a mesma utilizada na criação da aplicação. Entre `5` e `350` caracteres.
- `scope` `string`: Permissões de acesso solicitadas. Use uma ou mais das permissões listadas acima.
- `state` `string`: Permite repassar informações necessárias para a autorização. Pode ser usado para controle de acesso. Até `128` caracteres alfanuméricos.

## Responses

- `302`: Redirecionamento para a URL de autorização.

