# Criar e pagar com autenticação 3DS externa

Esse guia descreve como criar e pagar um pedido utilizando autenticação 3DS com validação externa ao sistema do PagBank. Portanto, você irá utilizar um serviço complementar ao PagBank para realizar a autenticação do usuário e depois, irá criar e pagar o pedido usando o sistema PagBank. Essa opção cobre pagamento utilizando Cartões de Crédito e Débito.

O sistema de autenticação de cartões 3DS é um protocolo de autenticação utilizado em transações online com cartão para garantir a segurança do pagamento. Ele pode exigir a validação do titular do cartão por meio de autenticação adicional, como senha, código de verificação ou reconhecimento biométrico.

## Autentique o cliente

Antes de realizar a chamada ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) você deverá autenticar o cliente. Como esse guia considera a validação externa dos dados do portador do cartão, é necessário que você contrate uma solução para a realização de autenticações 3DS. Portanto, a etapa de autenticação não será vinculada ao PagBank.

Para a autenticação, você fornecerá os dados do cartão, do cliente e do dispositivo ao serviço contratado. Baseadas nessa informação o serviço contratado irá realizar a autenticação, a qual pode ocorrer com desafio ou sem desafio.

- Sem desafio (sem atrito): o banco emissor do cartão entende que as informações fornecidas são suficientes para autenticar o consumidor
- Com desafio (com atrito): o banco emissor do cartão entende que as informações fornecidas não são suficientes para autenticar o consumidor. Assim, uma etapa adicional é necessária na qual o consumidor precisa realizar uma ação para validar a autenticidade. O recebimento de um código via SMS ou a abertura de um aplicativo são exemplos de desafios. No entanto, o tipo de desafio depende do banco emissor do cartão.

A finalização com sucesso do processo de autenticação do cliente irá te fornecer dados de autenticação. Dependo da bandeira do cartão, dados distintos podem ser retornados. Utilize a tabela a seguir para identificar e entender cada um dos parâmetros:

| Parâmetro | Descrição |
| --- | --- |
| `cavv` | Identificador único gerado em cenário de sucesso de autenticação. |
| `eci` | E-Commerce Indicator. Corresponde ao resultado da autenticação. |
| `xid` | Identificador de uma transação de um MPI (Visa/Elo). |
| `version` | Versão do protocolo 3DS utilizado na autenticação. |
| `dstrans_id` | Id da transação gerada pelo servidor de diretório durante uma autenticação (Mastercard). |

> Transações podem ser autenticadas ou não autenticadas
> A decisão de autenticar a transação é do emissor, isso significa que a sua transação pode não ser autenticada mesmo que tenha passado pelo fluxo de autenticação 3DS. Em casos de transação não autenticada, o liability em casos de chargeback de fraude não será do emissor.
> Pensando nisso o Pagbank construiu um motor de riscos que analisa de forma crítica as transações não autenticadas, buscando equilíbrio entre segurança e aprovação.

Esses dados devem ser enviados na requisição de criação e pagamento do pedido utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). Esse processo é descrito na próxima seção.

## Crie e pague o pedido

Com os dados da autenticação disponíveis e os dados do pedido, você pode criar o pedido. Para isso, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). **Os dados enviados na autenticação devem ser os mesmos enviados na criação do pedido, do contrário a transação será negada.**

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Como você está criando e pagando um pedido usando autenticação externa, é necessário que no corpo da requisição sejam adicionados os dados de autenticação 3DS. Essas informações devem ser adicionadas ao objeto `charges.authentication_method`. Além dos dados de autenticação, você deve definir o parâmetro `charges.authentication_method.type` com o valor `THREEDS`. Essa informação é **obrigatória** para operações com Cartão de Débito. Além disso, para que a captura da cobrança seja feita de forma automática, junto com a criação do pedido, você deve encaminhar o parâmetro `charges.payment_method.capture` com o valor `true`.

Abaixo você encontra exemplos de requisições e repostas feitas ao o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) utilizando Cartão de Crédito e de Débito.

### Request (Crédito)

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {TOKEN
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
          "number": "4111111111111111",
          "exp_month": "03",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "THREEDS",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "xid": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "eci": "05",
          "version": "2.1.0",
          "dstrans_id": "DIR_SERVER_TID"
        }
      }
    }
  ]
}'
```

### Response (Crédito)

```json
{
  "id": "ORDE_18D19860-8210-4682-9A4F-BD3308507E60",
  "reference_id": "ex-00001",
  "created_at": "2023-02-15T14:44:13.769-03:00",
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
      "id": "CHAR_E5E55042-E50E-4D2C-B49D-3E90185F61B6",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-15T14:44:14.292-03:00",
      "paid_at": "2023-02-15T14:44:16.000-03:00",
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
          "exp_month": "3",
          "exp_year": "2026",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "THREEDS",
          "eci": "05",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "xid": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "version": "2.1.0",
          "dstrans_id": "DIR_SERVER_TID",
          "status": "AUTHENTICATED"
        },
        "soft_descriptor": "MyStore"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_E5E55042-E50E-4D2C-B49D-3E90185F61B6",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_E5E55042-E50E-4D2C-B49D-3E90185F61B6/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_18D19860-8210-4682-9A4F-BD3308507E60",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_18D19860-8210-4682-9A4F-BD3308507E60/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

### Request (Débito)

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
        "type": "DEBIT_CARD",
        "card": {
          "number": "6550000000000001",
          "exp_month": "12",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        },
        "authentication_method": {
          "type": "THREEDS",
          "cavv": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "xid": "BwABBylVaQAAAAFwllVpAAAAAAA=",
          "eci": "05",
          "version": "2.1.0",
          "dstrans_id": "DIR_SERVER_TID"
        }
      }
    }
  ]
}'
```

### Response (Débito)

```bash
{
  "id": "ORDE_6F6C1FCA-4A83-4D81-9660-39279FF12E00",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T16:42:39.888-03:00",
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
      "id": "CHAR_F0796438-5C00-430B-8B15-D880F38CEBE0",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-08T16:42:40.378-03:00",
      "paid_at": "2023-02-08T16:42:41.000-03:00",
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
          "type": "THREEDS",
          "eci": "05",
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
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_F0796438-5C00-430B-8B15-D880F38CEBE0",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_F0796438-5C00-430B-8B15-D880F38CEBE0/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6F6C1FCA-4A83-4D81-9660-39279FF12E00",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_6F6C1FCA-4A83-4D81-9660-39279FF12E00/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.
