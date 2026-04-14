# Criar aplicação

`POST /oauth2/application`

Este endpoint permite criar um recurso de aplicação. Criar uma aplicação permite realizar ações em nome dos usuários.

> Acesse o guia do serviço de [Connect](/docs/connect) para mais informações sobre o funcionamento e as funcionalidades disponíveis.

## Headers

- `Authorization` `string`: Token de autenticação. Deve ser enviado no formato `Bearer <token>`.

## Body Params

- `name` `string`: Nome que irá identificar você ou sua plataforma na tela de permissões. Exemplo: `"name" gostaria de sua permissão para acessar ...`
- `description` `string`: Descrição da sua plataforma.
- `site` `string`: URL do seu site.
- `redirect_uri` `string`: URL para onde o usuário será direcionado após a aprovação das permissões. Esse parâmetro não é dinâmico. Obrigatório caso a autorização seja feita com o [Connect Authorization](/docs/connect-authorization).
- `logo` `string`: URL da imagem do seu logotipo. Envie a URL hospedada se estiver utilizando o Connect Authorization. Tamanho mínimo de `220x80` pixels e tamanho ideal ou máximo de `440x160` pixels.

## Responses

- `201`
- `400`

