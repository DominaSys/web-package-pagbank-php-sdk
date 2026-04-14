# Connect

Nesta página, você encontra o fluxo principal para conectar sua aplicação ao PagBank.

Aplicações como e-commerce, marketplaces e sistemas de conciliação podem usar Connect para agir em nome de um vendedor após o vínculo e a autorização da conta.

## Definição de usuário

Ao longo da documentação de Connect, o termo `usuário` se refere ao `vendedor`.

Esse vendedor é o responsável pela aplicação criada e terá a conta vinculada à integração.

## Fluxo principal

O fluxo de Connect pode ser visto em três etapas:

1. Criar a aplicação no PagBank.
2. Obter a autorização do vendedor.
3. Trocar o código de autorização por um `access_token`.

### 1. Criar aplicação

O primeiro passo é cadastrar a aplicação, informando dados como nome, descrição, URL de redirecionamento e logotipo.

Consulte:

- [Criar aplicação](../api/gerencia-connect/01-criar-aplicacao.md)
- [Consultar aplicação](../api/gerencia-connect/02-consultar-aplicacao.md)

### 2. Receber autorização do vendedor

Depois de criada a aplicação, você pode obter a autorização do vendedor por dois caminhos:

- [Connect Authorization](02-connect-authorization.md)
- [Connect via SMS](03-connect-sms.md)

Esses dois caminhos levam ao mesmo objetivo: obter um código de confirmação para a troca por `access_token`.

### 3. Obter access token

Com o código de confirmação em mãos, a aplicação faz a troca pelo `access_token`, que será usado nas chamadas futuras em nome do vendedor.

Consulte:

- [Obter access token](../api/gerencia-connect/05-obter-access-token.md)

## Operações complementares

Além do fluxo principal, Connect oferece operações de apoio:

- [Renovar access token](../api/gerencia-connect/06-renovar-access-token.md)
- [Revogar access token](../api/gerencia-connect/07-revogar-access-token.md)
- [Connect challenge](04-connect-challenge.md)

## Tokens e validação

O `access_token` deve ser armazenado e associado ao vendedor correspondente no seu sistema.

Quando o token expirar, use o `refresh_token` para renová-lo. Sempre que uma renovação ocorre, um novo `refresh_token` é emitido e o anterior deixa de valer.

## Códigos de erro

Consulte os cenários de erro do Connect em:

- [Códigos de erro](../api/01-codigos-erro.md)

## Próximos passos

Se você está começando a integração, siga esta ordem:

1. [Criar aplicação](../api/gerencia-connect/01-criar-aplicacao.md)
2. [Connect Authorization](02-connect-authorization.md) ou [Connect via SMS](03-connect-sms.md)
3. [Obter access token](../api/gerencia-connect/05-obter-access-token.md)
