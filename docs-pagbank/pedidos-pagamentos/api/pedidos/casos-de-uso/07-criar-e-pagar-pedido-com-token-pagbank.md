# Criar e pagar pedido com token PagBank

A tokenização PagBank é um serviço de segurança que permite armazenar de forma segura os dados dos cartões dos compradores. Os dados do cartão são salvos no fluxo de cobrança com cartão de crédito, onde você pode optar por salvar o cartão utilizado na compra. Assim, o token resultante pode ser utilizado em compras futuras, substituindo os dados do cartão.

Este modelo é recomendado para:

- Salvar o cartão do cliente para futuras compras ou cobranças.
- Oferecer compras por um clique, sem a necessidade de fornecer os dados do cartão já utilizados em uma compra anterior.
- Melhorar a experiência de compra na sua plataforma.

## Fluxo de utilização

A criação do token PagBank acontece durante a criação e pagamento utilizando o fluxo convencional descrito em [Criar e pagar com cartão](05-criar-e-pagar-um-pedido-com-cartao.md), conforme apresentado pelo fluxo abaixo.

Ao optar pela adoção do sistema de token, os dados essenciais do cliente para a efetivação de um pagamento são salvos no sistema do PagBank. Para o gerenciamento de compras futuras, o PagBank gera e retorna um token que substitui os dados do cartão. Esse token pode ser utilizado em pagamentos futuros do mesmo cliente, sem a necessidade de confirmar sua identidade.

> Considerações importantes
> - É importante destacar que o token só pode ser utilizado por quem o gera. Ou seja, não é possível fazer a transferência de um token.
> - O token é gerado mesmo se a cobrança seja negada.
> - Você pode gerar o token em operações de criação e pagamento de um pedido no mesmo request (operações de um passo), ou em operações onde você inicialmente cria o pedido e depois realiza a captura (operação em dois passos).

## Crie um token PagBank

Ao realizar o primeiro request ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você deve encaminhar o parâmetro `charges.payment_method.store` com o valor `true`. Dessa forma você estará indicando que além da cobrança, o sistema PagBank deverá realizar a tokenização do cartão utilizado na compra. Os parâmetros relacionados aos dados do cartão devem ser enviados normalmente.

A response da chamada conterá o token PagBank do cartão utilizado, independente se a transação foi aprovada ou rejeitada. Você pode acessar o token através do parâmetro `charges.payment_method.card.id`. Você deve salvar o token em seu sistema para futuras transações.

Abaixo você encontra exemplos de request para a criação e pagamento de um pedido onde foi requisitada a criação do token PagBank. Os dados da resposta também são apresentados, contendo o token vinculado ao cartão utilizado.

### Request

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Authorization: Bearer {
  {TOKEN
  }
}' \
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
          "number": "4111111111111111",
          "exp_month": "12",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "store": true
        }
      }
    }
  ]
}'
```

### Response

```json
{
  "id": "ORDE_1BD3B260-907B-46D3-99D4-9F646E9CBC6A",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T16:10:42.476-03:00",
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
      "id": "CHAR_53702DF2-E986-4581-AA66-9C8C199B1906",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T16:10:42.946-03:00",
      "paid_at": "2023-02-08T16:10:44.000-03:00",
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
          "id": "CARD_9806D4C1-1A4A-4DD7-8960-1171ECD8DE3D",
          "brand": "visa",
          "first_digits": "411111",
          "last_digits": "1111",
          "exp_month": "12",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "store": true
        },
        "soft_descriptor": "IntegracaoPagsegu"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_53702DF2-E986-4581-AA66-9C8C199B1906",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_53702DF2-E986-4581-AA66-9C8C199B1906/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_1BD3B260-907B-46D3-99D4-9F646E9CBC6A",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_1BD3B260-907B-46D3-99D4-9F646E9CBC6A/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

## Crie e pague um pedido com token PagBank

Após ter o token PagBank, para realizar a criação e pagamento de pedidos você irá fornecer o token no lugar dos dados do cartão. O token deve ser informado no parâmetro `charges.payment_method.card.id`. Adicionalmente, você pode encaminhar o nome do detentor do cartão e o CVV através do `charges.payment_method.card.security_code`.

Para que a captura da cobrança seja feita de forma automática, junto à criação do pedido, você deve encaminhar o parâmetro `charges.payment_method.capture` com o valor `true`.

Abaixo você encontra exemplos de requisição e resposta ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) utilizando o token PagBank.

### Request

```bash
curl --location --request POST 'https://sandbox.api.pagseguro.com/orders' \
--header 'Authorization: Bearer {{BEARER}}' \
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
                    "id": "CARD_9806D4C1-1A4A-4DD7-8960-1171ECD8DE3D",
                    "security_code": "123",
                    "holder": {
                      "name": "Jose da Silva",
                      "tax_id": "65544332211"
                    },
                    "store": true
                }
            }
        }
    ]
}'
```

### Response

```json
{
    "id": "ORDE_6AD3E748-A4E4-48D4-B80C-14CE06FC66FB",
    "reference_id": "ex-00001",
    "created_at": "2023-02-08T16:11:24.170-03:00",
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
            "id": "CHAR_BAD68EB1-65B8-47FF-84C6-528496513C82",
            "reference_id": "referencia da cobranca",
            "status": "PAID",
            "created_at": "2023-02-08T16:11:24.663-03:00",
            "paid_at": "2023-02-08T16:11:25.000-03:00",
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
                    "id": "CARD_9806D4C1-1A4A-4DD7-8960-1171ECD8DE3D",
                    "brand": "visa",
                    "first_digits": "411111",
                    "last_digits": "1111",
                    "exp_month": "12",
                    "exp_year": "2026",
                    "holder": {
                      "name": "Jose da Silva",
                      "tax_id": "65544332211"
                    },
                    "store": true
                },
                "soft_descriptor": "IntegracaoPagsegu"
            },
            "links": [
                {
                    "rel": "SELF",
                    "href": "https://sandbox.api.pagseguro.com/charges/CHAR_BAD68EB1-65B8-47FF-84C6-528496513C82",
                    "media": "application/json",
                    "type": "GET"
                },
                {
                    "rel": "CHARGE.CANCEL",
                    "href": "https://sandbox.api.pagseguro.com/charges/CHAR_BAD68EB1-65B8-47FF-84C6-528496513C82/cancel",
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
            "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6AD3E748-A4E4-48D4-B80C-14CE06FC66FB",
            "media": "application/json",
            "type": "GET"
        },
        {
            "rel": "PAY",
            "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6AD3E748-A4E4-48D4-B80C-14CE06FC66FB/pay",
            "media": "application/json",
            "type": "POST"
        }
    ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.
