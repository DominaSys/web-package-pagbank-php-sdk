# dominasys/pagbank-php-sdk

SDK PHP puro para integrar aplicações com a API do PagBank.

Este pacote nasce com uma regra simples: o core não depende de Laravel. A ideia é servir como base pública, reutilizável em qualquer aplicação PHP, enquanto as integrações específicas de framework ficam para camadas opcionais depois.

## O que este pacote resolve

- autenticação e configuração de ambiente;
- client HTTP centralizado para a API do PagBank;
- módulo `Connect` para fluxo OAuth base;
- módulo `Accounts` para criação e consulta de conta;
- módulo `Cards` para validação e armazenamento de cartão;
- módulo `Orders` para criação, consulta e pagamento de pedidos;
- módulo `Charges` para consulta, captura e cancelamento de cobranças;
- resposta e erro padronizados para a API;
- base pronta para crescer para checkout, webhooks e recorrência.

## Escopo do v1

O pacote já cobre o núcleo de `Connect`, `Accounts`, `Cards`, `Orders` e `Charges`:

- criar aplicação;
- consultar aplicação;
- gerar URL de autorização via `Connect Authorization`;
- trocar `code` por `access_token`;
- renovar `access_token`;
- revogar `access_token`;
- criar conta;
- consultar conta;
- criar pedido;
- consultar pedido;
- pagar pedido.

- consultar cobrança;
- capturar cobrança;
- cancelar cobrança.

O fluxo de cartões armazenados fica em `Cards`:

- validar e armazenar cartão.

Ainda não entram no v1:

- Connect via SMS;
- Connect challenge;
- sessão 3DS;
- consulta de taxas de transação;
- checkout;
- cartões;
- recorrência;
- webhooks.

## Por que esse pacote existe

O objetivo é evitar dependência de wrappers legados ou de intermediários de terceiros. A DominaSys precisa de uma base estável, previsível e com API pública própria para permitir integrações diretas com o PagBank.

## Instalação

```bash
composer require dominasys/pagbank-php-sdk
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
use Dominasys\PagBank\Orders\Dto\CreateOrderData;
use Dominasys\PagBank\Orders\Dto\OrderAddressData;
use Dominasys\PagBank\Orders\Dto\OrderCustomerData;
use Dominasys\PagBank\Orders\Dto\OrderItemData;
use Dominasys\PagBank\Orders\Dto\OrderPhoneData;
use Dominasys\PagBank\Orders\Dto\OrderShippingData;
use Dominasys\PagBank\Orders\Enums\OrderCustomerPhoneType;
use Dominasys\PagBank\Cards\Dto\CardHolderData;
use Dominasys\PagBank\Cards\Dto\CardStoreData;
use Dominasys\PagBank\Charges\Dto\ChargeAmountData;
use Dominasys\PagBank\Charges\Dto\ChargeCaptureData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelData;
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

### Pedidos

```php
$orders = $sdk->orders();

$order = $orders->createOrder(
    new CreateOrderData(
        referenceId: 'order-123',
        customer: new OrderCustomerData(
            taxId: '12345678900',
            name: 'José Carlos Silva',
            email: 'jose@domina.example',
            phones: [
                new OrderPhoneData(
                    country: '55',
                    area: '11',
                    number: '999999999',
                    type: OrderCustomerPhoneType::Mobile,
                ),
            ],
        ),
        items: [
            new OrderItemData(
                referenceId: 'item-001',
                name: 'Plano mensal',
                quantity: 1,
                unitAmount: 4990,
            ),
        ],
        shipping: new OrderShippingData(
            address: new OrderAddressData(
                street: 'Rua Exemplo',
                number: '100',
                complement: 'Sala 12',
                locality: 'Centro',
                city: 'São Paulo',
                regionCode: 'SP',
                country: 'BRA',
                postalCode: '01000000',
            ),
        ),
    ),
    idempotencyKey: 'order-123',
);

$orderId = $order->id();
```

### Cobranças

```php
$charges = $sdk->charges();

$charge = $charges->getCharge('CHAR_123');

$captured = $charges->captureCharge(
    'CHAR_123',
    new ChargeCaptureData(
        amount: new ChargeAmountData(150099),
    ),
);

$charges->cancelCharge(
    'CHAR_123',
    new ChargeCancelData(
        amount: new ChargeAmountData(150099),
    ),
);
```

### Cartões

```php
$cards = $sdk->cards();

$storedCard = $cards->validateAndStoreCard(
    CardStoreData::encrypted(
        'encrypted-value',
        new CardHolderData('Jose da Silva', '12345678909'),
    ),
);
```

## Roadmap

Depois do núcleo de `Connect`, `Accounts`, `Cards`, `Orders` e `Charges`, o pacote evolui para:

- Connect via SMS;
- Connect challenge;
- Checkout;
- gestão ampliada de cartões;
- recorrência;
- webhooks;
- bridge Laravel opcional, se fizer sentido.

## Convenções

- PHP 8.2+;
- namespace `Dominasys\PagBank\`;
- respostas normalizadas em objetos próprios;
- erros da API mapeados para exceções do pacote;
- sem dependência de Laravel no core.
