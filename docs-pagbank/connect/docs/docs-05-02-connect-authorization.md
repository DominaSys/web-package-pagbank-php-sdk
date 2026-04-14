# Connect Authorization

Use Connect Authorization quando quiser redirecionar o vendedor para uma página PagBank para conceder permissões.

Esse fluxo é mais próximo do OAuth padrão e funciona bem quando a experiência de autorização pode acontecer fora do seu sistema.

## Quando usar

Use esse caminho quando você quiser:

- solicitar permissões de forma explícita ao vendedor;
- redirecionar para uma página hospedada pelo PagBank;
- receber um `code` de autorização para troca posterior por `access_token`.

## Visão geral do fluxo

1. Redirecione o vendedor para a URL de autorização.
2. O vendedor faz login, se necessário.
3. O vendedor aprova as permissões.
4. Sua aplicação recebe o `code` de autorização.
5. Troque o `code` por `access_token`.

## 1. Redirecione o usuário

A URL de autorização recebe parâmetros como `client_id`, `response_type`, `redirect_uri`, `scope` e `state`.

Consulte a referência técnica:

- [Solicitar autorização via Connect Authorization](../api/gerencia-connect/03-solicitar-autorizacao-via-connect-authorization.md)

## 2. Login do vendedor

Se o vendedor já possui conta PagBank, ele fará login antes de visualizar as permissões.

No Sandbox, você deve usar as credenciais de teste disponibilizadas para o ambiente.

## 3. Aprovação pelo vendedor

Após revisar as permissões, o vendedor pode aprovar ou negar o acesso.

Se aprovar, será redirecionado para a `redirect_uri` com o código de autorização.

## 4. Obtenha o access token

Com o `code` recebido na `redirect_uri`, faça a troca para obter o `access_token`.

Consulte:

- [Obter access token](../api/gerencia-connect/05-obter-access-token.md)

## Permissões

O `scope` define quais ações a aplicação pode executar em nome do vendedor.

Entre os escopos disponíveis, estão permissões para:

- visualizar pedidos e cobranças;
- criar pedidos e cobranças;
- fazer reembolsos;
- consultar dados cadastrais;
- criar, visualizar e atualizar checkouts.

## Duração do código

O `code` gerado na aprovação é de uso único e tem validade curta.

## Observação

Este conteúdo explica o fluxo. Para a chamada de API, use a página de referência correspondente:

- [Gerência Connect](../api/gerencia-connect/01-criar-aplicacao.md)
