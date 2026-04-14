# dominasys/sdk-pagbank-php

SDK PHP puro para integrar aplicações com a API do PagBank.

Este pacote nasce com uma regra simples: o core não depende de Laravel. A ideia é servir como base pública, reutilizável em qualquer aplicação PHP, enquanto as integrações específicas de framework ficam para camadas opcionais depois.

## O que este pacote resolve

- autenticação e configuração de ambiente;
- client HTTP centralizado para a API do PagBank;
- módulo `Connect` para fluxo OAuth base;
- resposta e erro padronizados para a API;
- base pronta para crescer para pedidos, cobranças, checkout, cartões, webhooks e recorrência.

## Escopo do v1

O primeiro release cobre o essencial do `Connect`:

- criar aplicação;
- consultar aplicação;
- gerar URL de autorização via `Connect Authorization`;
- trocar `code` por `access_token`;
- renovar `access_token`;
- revogar `access_token`.

Ainda não entram no v1:

- Connect via SMS;
- Connect challenge;
- pedidos e cobranças;
- checkout;
- cartões;
- recorrência;
- webhooks.

## Por que esse pacote existe

O objetivo é evitar dependência de wrappers legados ou de intermediários de terceiros. A DominaSys precisa de uma base estável, previsível e com API pública própria para permitir integrações diretas com o PagBank.

## Instalação

```bash
composer require dominasys/sdk-pagbank-php
```

## Uso básico

```php
use Dominasys\PagBank\Environment;
use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Connect\Dto\AuthorizationScopes;
use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Accounts\Dto\AccountData;
use Dominasys\PagBank\Accounts\Dto\AccountPersonData;
use Dominasys\PagBank\Accounts\Dto\AccountTosAcceptanceData;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\Endpoints;
use Dominasys\PagBank\PagBank;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Transport;

$sdk = PagBank::make(
    Configuration::make(
        endpoints: new Endpoints(environment: Environment::Sandbox),
        credentials: new Credentials(
            bearerToken: $_ENV['PAGBANK_BEARER_TOKEN'] ?? null,
            clientId: $_ENV['PAGBANK_CLIENT_ID'] ?? null,
            clientSecret: $_ENV['PAGBANK_CLIENT_SECRET'] ?? null,
        ),
        transport: new Transport(),
    ),
);

$connect = $sdk->connect();
```

### Criar aplicação

```php
$response = $connect->createApplication(new CreateApplicationData(
    name: 'DominaPay',
    description: 'Plataforma de pagamentos da DominaSys',
    site: 'https://domina.example',
    redirectUri: 'https://domina.example/callback/pagbank',
    logo: 'https://domina.example/logo.png',
));

$clientId = $response->clientId();
```

### URL de autorização

```php
$authorizationUrl = $connect->authorizationUrl(
    new AuthorizationUrlData(
        clientId: 'seu-client-id',
        redirectUri: 'https://domina.example/callback/pagbank',
        scopes: new AuthorizationScopes('payments.read', 'payments.create'),
        state: 'tenant-123',
    ),
);
```

### Trocar código por token

```php
$token = $connect->exchangeAuthorizationCode(
    code: $_GET['code'],
    redirectUri: 'https://domina.example/callback/pagbank',
);

$accessToken = $token->accessToken();
```

### Contas

```php
$accounts = $sdk->accounts();

$account = $accounts->createAccount(AccountData::buyer(
    email: 'buyer@domina.example',
    person: new AccountPersonData(
        name: 'José Carlos Silva',
        birthDate: '1991-10-10',
        motherName: 'Maria Silva',
        taxId: '12345678900',
    ),
    tosAcceptance: new AccountTosAcceptanceData(
        userIp: '127.0.0.1',
        date: '2024-01-01T10:00:00-03:00',
    ),
));

$accountId = $account->id();
```

## Roadmap

Depois do Connect base, o pacote evolui para:

- Connect via SMS;
- Connect challenge;
- Orders e Charges;
- Checkout;
- cartões salvos;
- recorrência;
- webhooks;
- bridge Laravel opcional, se fizer sentido.

## Convenções

- PHP 8.2+;
- namespace `Dominasys\PagBank\`;
- respostas normalizadas em objetos próprios;
- erros da API mapeados para exceções do pacote;
- sem dependência de Laravel no core.
