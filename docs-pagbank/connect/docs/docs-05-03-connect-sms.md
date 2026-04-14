# Connect via SMS

Use Connect via SMS quando a autorização precisar acontecer dentro do seu sistema, sem redirecionar o vendedor para uma página PagBank.

Esse fluxo é útil quando você quer conduzir a autorização de forma mais transparente para o usuário.

## Quando usar

Use esse caminho quando a integração precisar:

- solicitar autorização diretamente com e-mail e dados do vendedor;
- receber um código via SMS;
- trocar esse código por `access_token`.

## Visão geral do fluxo

1. Solicite a autorização.
2. O vendedor recebe o SMS de confirmação.
3. Sua aplicação recebe o `sms_code`.
4. Troque o `sms_code` por `access_token`.

## 1. Solicite a autorização

Para pedir a autorização, use os dados do vendedor e os identificadores da aplicação.

Consulte:

- [Solicitar autorização via SMS](../api/gerencia-connect/04-solicitar-autorizacao-via-sms.md)

## 2. Confirmação pelo vendedor

O vendedor recebe o código de confirmação no telefone cadastrado.

Esse código é o `sms_code` que sua aplicação precisa capturar para seguir com a troca.

## 3. Obtenha o access token

Use o `sms_code` para obter o `access_token` no endpoint de troca.

Consulte:

- [Obter access token](../api/gerencia-connect/05-obter-access-token.md)

## Simulação no Sandbox

No ambiente Sandbox, você pode simular cenários de autorização e de obtenção do token.

Isso ajuda a testar:

- sucesso na solicitação;
- bloqueio por excesso de SMS;
- obtenção do token com código válido;
- falha por código inválido ou bloqueado.

## Limites do fluxo

No Connect via SMS, a autorização é mais restrita do que no fluxo padrão.

Em geral, use esse caminho apenas quando o seu caso de uso não exigir redirecionamento para a tela do PagBank.

## Próximo passo

Depois de obter o `access_token`, siga para:

- [Connect](01-connect.md)
- [Obter access token](../api/gerencia-connect/05-obter-access-token.md)
