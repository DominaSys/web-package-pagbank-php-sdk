# Criar e pagar um pedido com cartão

Esse guia descreve como criar e pagar um pedido com Cartão de Crédito sem redirecionar o seu cliente para o ambiente do PagBank. Dessa forma, você tem maior controle sobre a experiência do cliente durante o processo de pagamento. Para implementar esse processo você irá utilizar:

1. O SDK do PagBank para criptografar os dados do cartão.
2. O endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) disponibilizado pelo PagBank.

## Criptografe o cartão

Se o seu backend já recebe os dados brutos do cartão, o SDK PHP do PagBank pode gerar o `encryptedCard` antes do envio ao endpoint `Criar pedido`.

Isso mantém a mesma carga criptografada esperada pelo PagBank, sem depender de criptografia no navegador.

### php

```php
use Dominasys\PagBank\Cards\Dto\CardEncryptData;

$encrypted = $sdk->cards()->encryptCard(new CardEncryptData(
    publicKey: $_ENV['PAGBANK_PUBLIC_KEY'],
    number: '4242424242424242',
    expMonth: 12,
    expYear: 2030,
    holder: 'Nome Sobrenome',
    securityCode: '123',
));

if ($encrypted->hasErrors()) {
    foreach ($encrypted->errors() as $error) {
        // use $error->code e $error->message
    }
}
```

Depois, envie o valor retornado por `encryptedCard()` em `charges.payment_method.card.encrypted`.

## Crie e pague o pedido

Com os dados do cartão criptografados em mãos e os dados do pedido, você pode criar o pedido. Para isso, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Os dados resultantes da encriptação do Cartão de Crédito (`encryptedCard`) devem ser adicionados ao campo `charges.payment_method.card.encrypted`. Além disso, para que a captura da cobrança seja feita de forma automática, junto com a criação do pedido, você deve encaminhar o parâmetro `charges.payment_method.capture` com o valor `true`.

Na página do endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) você encontra exemplos de request e reponse nomeados **Criar pedido com cartão**. Esses mesmos exemplos são apresentados a seguir.

### Request

```bash
curl --location --request POST 'https://sandbox.api.pagseguro.com/orders' \
--header 'Authorization: Bearer {{}}' \
--header 'Content-Type: application/json' \
--data-raw '{
    "reference_id": "ex-00001",
    "customer": {
        "name": "Jose da Silva",
        "email": "email@test.com",
        "tax_id": "12345678909",
        "phones": [
            {
                "country": "55",
                "area": "11",
                "number": "999999999",
                "type": "MOBILE"
            }
        ]
    },
    "items": [
        {
            "reference_id": "referencia do item",
            "name": "nome do item",
            "quantity": 1,
            "unit_amount": 500
        }
    ],
    "shipping": {
        "address": {
            "street": "Avenida Brigadeiro Faria Lima",
            "number": "1384",
            "complement": "apto 12",
            "locality": "Pinheiros",
            "city": "São Paulo",
            "region_code": "SP",
            "country": "BRA",
            "postal_code": "01452002"
        }
    },
    "notification_urls": [
        "https://meusite.com/notificacoes"
    ],
    "charges": [
        {
            "reference_id": "referencia da cobranca",
            "description": "descricao da cobranca",
            "amount": {
                "value": 500,
                "currency": "BRL"
            },
            "payment_method": {
                "type": "CREDIT_CARD",
                "installments": 1,
                "capture": true,
                "card": {
                    "encrypted":"V++53ir0qvoK/rUSzNjCqP8Hz9ZTa+HohR779n63CV+NvCeYj4J4lQevL4NKN7Di3BxKQGqfQW5cfS7/4rHw4w8URuOV/j/mGau2GXxkKQ6/szJ6BQr//C4e4XgfCHDwcONQhuPDHMdOB1C+4lzyBbsPJUZ/8TUQrxhMMiMFjwGeg62uf7cUqdFjp+Q5dqJXwhLgH3d1EoX+JKStBLqVzF0lW3gHtFOyfvFhuxxBgB0xrzTKfbTqnL5aSYBoGXRFM0gLodMm6knx7bW+syThxyQffnaigCwj2aNohsu+fuXII+3WnlgrHQxaBx3ChRuWKy+loV2L2USiGulp/bPEcg==",
                    "store": false
                },
              	"holder": {
                  "name": "Jose da Silva",
                  "tax_id": "65544332211"
                }
            }
        }
    ]
}'
```

### Response

```json
{
    "id": "ORDE_1E38BD2E-F2CC-4D9C-9727-787CDFBCA7CE",
    "reference_id": "ex-00001",
    "created_at": "2023-02-08T15:15:11.408-03:00",
    "customer": {
        "name": "Jose da Silva",
        "email": "email@test.com",
        "tax_id": "12345678909",
        "phones": [
            {
                "type": "MOBILE",
                "country": "55",
                "area": "11",
                "number": "999999999"
            }
        ]
    },
    "items": [
        {
            "reference_id": "referencia do item",
            "name": "nome do item",
            "quantity": 1,
            "unit_amount": 500
        }
    ],
    "shipping": {
        "address": {
            "street": "Avenida Brigadeiro Faria Lima",
            "number": "1384",
            "complement": "apto 12",
            "locality": "Pinheiros",
            "city": "São Paulo",
            "region_code": "SP",
            "country": "BRA",
            "postal_code": "01452002"
        }
    },
    "charges": [
        {
            "id": "CHAR_67FC568B-00D8-431D-B2E7-755E3E6C66A0",
            "reference_id": "referencia da cobranca",
            "status": "PAID",
            "created_at": "2023-02-08T15:15:11.881-03:00",
            "paid_at": "2023-02-08T15:15:12.000-03:00",
            "description": "descricao da cobranca",
            "amount": {
                "value": 500,
                "currency": "BRL",
                "summary": {
                    "total": 500,
                    "paid": 500,
                    "refunded": 0
                }
            },
            "payment_response": {
                "code": "20000",
                "message": "SUCESSO",
                "reference": "032416400102"
            },
            "payment_method": {
                "type": "CREDIT_CARD",
                "installments": 1,
                "capture": true,
                "card": {
                    "brand": "visa",
                    "first_digits": "411111",
                    "last_digits": "1111",
                    "exp_month": "12",
                    "exp_year": "2026",
                    "holder": {
                        "name": "Joãozinho da Silva",
                      	"tax_id": "65544332211"
                    },
                    "store": false
                },
                "soft_descriptor": "IntegracaoPagsegu"
            },
            "links": [
                {
                    "rel": "SELF",
                    "href": "https://sandbox.api.pagseguro.com/charges/CHAR_67FC568B-00D8-431D-B2E7-755E3E6C66A0",
                    "media": "application/json",
                    "type": "GET"
                },
                {
                    "rel": "CHARGE.CANCEL",
                    "href": "https://sandbox.api.pagseguro.com/charges/CHAR_67FC568B-00D8-431D-B2E7-755E3E6C66A0/cancel",
                    "media": "application/json",
                    "type": "POST"
                }
            ]
        }
    ],
    "notification_urls": [
        "https://meusite.com/notificacoes"
    ],
    "links": [
        {
            "rel": "SELF",
            "href": "https://sandbox.api.pagseguro.com/orders/ORDE_1E38BD2E-F2CC-4D9C-9727-787CDFBCA7CE",
            "media": "application/json",
            "type": "GET"
        },
        {
            "rel": "PAY",
            "href": "https://sandbox.api.pagseguro.com/orders/ORDE_1E38BD2E-F2CC-4D9C-9727-787CDFBCA7CE/pay",
            "media": "application/json",
            "type": "POST"
        }
    ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.
