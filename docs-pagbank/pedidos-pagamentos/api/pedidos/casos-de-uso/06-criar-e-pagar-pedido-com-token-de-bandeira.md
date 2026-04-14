# Criar e pagar pedido com token de bandeira

A Tokenização de Bandeira é um serviço que substitui os dados sensíveis do cartão do portador (PAN, CVV, Validade) por "tokens", que são números alternativos. Esses tokens substituem o PAN de 16 dígitos, o CVV de 3 dígitos e a validade do cartão (mm/aa). O processo de tokenização oferece benefícios significativos:

- Aumento de Segurança: A tecnologia protege as informações do titular do cartão, substituindo os dados sensíveis originais por números alternativos exclusivos.
- Aumento da Taxa de Aprovação: As transações tokenizadas pelas bandeiras têm um índice de aprovação superior ao das transações não tokenizadas.

Esse recurso é ideal para aqueles que:

- Desejam adicionar uma camada de proteção ao fluxo transacional.
- Buscam aumentar a taxa de aprovação de transações online.
- São Token Requestors ou têm integração com algum Token Requestor para solicitar Tokens de Bandeira.

É importante destacar que a criação do token de bandeira é uma ação não vinculada aos serviços PagBank.

> Token de bandeira e tokenização PagBank
> O pedido com token de bandeira difere da tokenização PagBank. No processo de tokenização PagBank, o processo de tokenização é realizado pelo PagBank e as informações ficam salvas nos servidores PagBank.

| Pedido com Token de Bandeira | Tokenização PagBank |
| --- | --- |
| No pedido com token de bandeira, os dados utilizados para a criação de uma transação são provenientes do próprio Token de Bandeira fornecido pelas bandeiras de Cartão de Crédito. Esse token possui características similares a um Cartão de Crédito ou Débito. | No caso da Tokenização PagBank, o Cartão de Crédito é armazenado pelo PagBank para ser utilizado em compras futuras. Na resposta da requisição de pagamento do pedido, você receberá o token do cartão em `payment_method.card.id`. |

## Fluxo de utilização

Como apresentado no fluxo de operação a seguir, a obtenção do token é um processo prévio à criação e pagamento de um pedido. Você precisa ter uma conexão com um Token Requestor para gerar o token. O token gerado por esse serviço é utilizado para criar e pagar pedidos junto ao PagBank.

A tokenização de bandeira é um recurso que permite a criação de um pedido com cobrança de pagamento utilizando um token de bandeira previamente gerado. Esse processo consiste na substituição dos dados sensíveis do cartão do portador por tokens de pagamento gerados pelas bandeiras de Cartão de Crédito.

## Gere o token de bandeira

Para implementar a funcionalidade de tokenização de bandeira, é necessário contratar uma solução junto às próprias bandeiras de Cartão de Crédito.

Vale ressaltar que a estrutura e os dados solicitados para a cobrança com tokens das bandeiras Visa e Mastercard são os mesmos. No entanto, a cobrança com token da bandeira Elo requer dados diferentes.

Ao obter sucesso na tokenização, você receberá os dados do token e, em alguns casos, dados adicionais de tokenização. Essas informações, apresentadas na tabela a seguir, devem ser fornecidas ao criar um Pedido com token de bandeira.

| Bandeira | Parâmetro | Descrição |
| --- | --- | --- |
| Mastercard e Visa | `cryptogram` | Criptograma gerado pela bandeira. |
| `requestor_id` | Identificador de quem gerou o token de bandeira (Token Requestor). |
| `wallet` | Tipo de carteira que armazenou o token de bandeira. |
| `ecommerce_domain` | Identificador do domínio de origem da transação. |
| `assurance_level` | Conteúdo que indica o nível de confiança do token de bandeira. |
| Elo | `cavv` | Identificador único gerado em cenário de sucesso de autenticação. |
| `eci` | E-Commerce Indicator. |

## Crie e pague o pedido

Com os dados do token de bandeira em mãos e os dados do pedido, você pode criar o pedido. Para isso, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Ao criar um pedido utilizando dados das bandeiras Visa ou Mastercard, os dados adicionais de tokenização devem ser inseridos no objeto `charges.payment_method.token_data`. Para o caso da bandeira Elo, os dados de autenticação devem ser inseridos no objeto `charges.payment_method.authentication_method`. Além disso, para que a captura da cobrança seja feita de forma automática, junto à criação do pedido, você deve encaminhar o parâmetro `charges.payment_method.capture` com o valor `true`.

Nas subseções a seguir você encontra exemplos de requisições e respostas utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.

### Exemplos bandeiras VISA e MASTERCARD (Crédito e Débito):

### Request (Crédito)

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {
  {token
  }
}' \
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
          "network_token": "5454555555555555",
          "exp_month": "12",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "token_data": {
            "requestor_id": "12345678901",
            "wallet": "APPLE_PAY",
            "cryptogram": "BNQ1qJkmBYWiAAuzyDDhAoABFA==",
            "ecommerce_domain": "br.com.pagseguro",
            "assurance_level": 88
          }
        }
      }
    }
  ]
}'
```

### Response (Crédito)

```json
{
  "id": "ORDE_A0C334AD-244E-4175-A04F-A69C812FFFD0",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T18:29:03.344-03:00",
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
      "id": "CHAR_84E0D7ED-A84E-4098-A289-6A7E4E09DAB2",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T18:29:03.835-03:00",
      "paid_at": "2023-02-08T18:29:05.000-03:00",
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
          "brand": "mastercard",
          "first_digits": "545455",
          "last_digits": "5555",
          "exp_month": "12",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "token_data": {
            "requestor_id": "12345678901",
            "wallet": "APPLE_PAY",
            "ecommerce_domain": "br.com.pagseguro",
            "assurance_level": 88
          }
        },
        "soft_descriptor": "IntegracaoPagsegu"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_84E0D7ED-A84E-4098-A289-6A7E4E09DAB2",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_84E0D7ED-A84E-4098-A289-6A7E4E09DAB2/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_A0C334AD-244E-4175-A04F-A69C812FFFD0",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_A0C334AD-244E-4175-A04F-A69C812FFFD0/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

### Request (Débito)

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {
  {token
  }
}' \
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
        "type": "DEBIT_CARD",
        "card": {
          "network_token": "4111111111111111",
          "exp_month": "03",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "token_data": {
            "requestor_id": "12345678901",
            "wallet": "APPLE_PAY",
            "cryptogram": "BNQ1qJkmBYWiAAuzyDDhAoABFA==",
            "ecommerce_domain": "br.com.pagseguro",
            "assurance_level": 99
          }
        },
        "authentication_method": {
          "type": "THREEDS",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "xid": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "eci": "01",
          "version": "2.1.0",
          "dstrans_id": "DIR_SERVER_TID"
        }
      }
    }
  ]
}'
```

### Response (Débito)

```json
{
  "id": "ORDE_4D5BF33A-2D5C-472A-9191-35AC4EAAD082",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T18:30:32.028-03:00",
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
      "id": "CHAR_783542CE-D2D4-45DD-B965-191CEB353026",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T18:30:32.519-03:00",
      "paid_at": "2023-02-08T18:30:34.000-03:00",
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
        "type": "DEBIT_CARD",
        "card": {
          "brand": "visa",
          "first_digits": "411111",
          "last_digits": "1111",
          "exp_month": "3",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "token_data": {
            "requestor_id": "12345678901",
            "wallet": "APPLE_PAY",
            "ecommerce_domain": "br.com.pagseguro",
            "assurance_level": 99
          }
        },
        "authentication_method": {
          "type": "THREEDS",
          "eci": "01",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "xid": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "version": "2.1.0",
          "dstrans_id": "DIR_SERVER_TID",
          "status": "AUTHENTICATED"
        },
        "soft_descriptor": "IntegracaoPagsegu"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_783542CE-D2D4-45DD-B965-191CEB353026",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_783542CE-D2D4-45DD-B965-191CEB353026/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_4D5BF33A-2D5C-472A-9191-35AC4EAAD082",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_4D5BF33A-2D5C-472A-9191-35AC4EAAD082/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

### Exemplos para bandeira ELO (Crédito e Débito):

### Request (Crédito)

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {
  {token
  }
}' \
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
        "soft_descriptor": "My Store",
        "card": {
          "network_token": "6550000000000001",
          "exp_month": "12",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "INAPP",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "eci": "04"
        }
      }
    }
  ]
}'
```

### Response (Crédito)

```json
{
  "id": "ORDE_6B853515-385F-4463-96F5-3CBFFD1C4309",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T18:34:08.746-03:00",
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
      "id": "CHAR_A76DCB77-B856-4726-9B6A-F1FA012E81E3",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T18:34:09.220-03:00",
      "paid_at": "2023-02-08T18:34:10.000-03:00",
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
          "brand": "elo",
          "first_digits": "655000",
          "last_digits": "0001",
          "exp_month": "12",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "INAPP",
          "eci": "04",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA="
        },
        "soft_descriptor": "MyStore"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_A76DCB77-B856-4726-9B6A-F1FA012E81E3",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_A76DCB77-B856-4726-9B6A-F1FA012E81E3/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6B853515-385F-4463-96F5-3CBFFD1C4309",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6B853515-385F-4463-96F5-3CBFFD1C4309/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

### Request (Débito)

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {
  {token
  }
}' \
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
        "type": "DEBIT_CARD",
        "card": {
          "network_token": "6550000000000001",
          "exp_month": "12",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "INAPP",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "eci": "04"
        }
      }
    }
  ]
}'
```

### Response (Débito)

```json
{
  "id": "ORDE_DD89BC61-1D4F-4B35-9A1F-3B88C0481B27",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T18:35:13.398-03:00",
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
      "id": "CHAR_C40A96D0-0FD1-4248-929F-A38C94ED6458",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T18:35:13.896-03:00",
      "paid_at": "2023-02-08T18:35:15.000-03:00",
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
        "type": "DEBIT_CARD",
        "card": {
          "brand": "elo",
          "first_digits": "655000",
          "last_digits": "0001",
          "exp_month": "12",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "INAPP",
          "eci": "04",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA="
        },
        "soft_descriptor": "IntegracaoPagsegu"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_C40A96D0-0FD1-4248-929F-A38C94ED6458",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_C40A96D0-0FD1-4248-929F-A38C94ED6458/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_DD89BC61-1D4F-4B35-9A1F-3B88C0481B27",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_DD89BC61-1D4F-4B35-9A1F-3B88C0481B27/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```
