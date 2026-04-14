# Criar pedido com repasse de taxa ao comprador

Esse guia descreve como criar um um pedido com pagamento parcelado e com a taxa repassados ao comprador. As taxas das parcelas podem ser repassados de forma integral ou parcial.

## Etapas necessárias

Essa operação é dividida em duas etapas:

1. Na primeira etapa você irá consultar os planos de pagamento. Para isso você utilizará a API de Fees disponibilizada pelo PagBank.
2. Na segunda etapa você irá criar o pedido com pagamento parcelado e repasse da taxa ao comprador. Para isso você utilizará a API de Pedidos disponibilizada pelo PagBank.

## Consulte as taxas para o parcelamento de uma transação

Nesta etapa inicial, o integrador deve consultar as opções de parcelamento disponíveis para o comprador. As informações necessárias para realizar essa consulta são apresentadas na tabela a seguir:

| Parâmetro | Descrição |
| --- | --- |
| `payment_methods` | Meios de pagamento para os quais o integrador deseja obter informações sobre as taxas de repasse. |
| `value` | Valor original da transação. |
| `max_installments` | Número máximo de parcelas permitidas, independentemente do repasse de taxa ao comprador. |
| `max_installments_no_interest` | Número de parcelas em que o vendedor assumirá a taxa de parcelamento. |
| `credit_card_bin` | Número do cartão. |

> Valor Mínimo da Parcela
> O valor mínimo de uma parcela da transação é de R$ 5.

Para realizar essa consulta você irá utilizar o endpoint [Consultar taxas de uma transação](/reference/consultar-taxas-transacao). Para te auxiliar a entender o processo, um exemplo é descrito na sequência.

Nesse exemplo é realizada uma consulta sobre os planos de parcelamento para uma compra com a bandeira MasterCard, no valor de R$100,00, que pode ser parcelada em até 10 vezes. O vendedor assumirá a taxa de parcelamento em até 4 parcelas. Isso significa que se o comprador escolher parcelar em até 4 vezes, a transação será feita sem taxa ao comprador. Caso contrário, o comprador irá arcar com os encargos das parcelas adicionais. A seguir são apresentados a requisição e a resposta associadas a esse exemplo.

### Request

```bash
curl --location --request GET 'https://sandbox.api.pagseguro.com/charges/fees/calculate?payment_methods=CREDIT_CARD&value=10000&max_installments=10&max_installments_no_interest=4&credit_card_bin=552100' \
--header 'Authorization: {{token}}' \
--header 'Content-Type: application/json'
```

### Response

```json
{
  "payment_methods": {
    "credit_card": {
      "mastercard": {
        "installment_plans": [
          {
            "installments": 1,
            "installment_value": 10000,
            "interest_free": true,
            "amount": {
              "value": 10000,
              "currency": "BRL"
            }
          },
          {
            "installments": 2,
            "installment_value": 5000,
            "interest_free": true,
            "amount": {
              "value": 10000,
              "currency": "BRL"
            }
          },
          {
            "installments": 3,
            "installment_value": 3333,
            "interest_free": true,
            "amount": {
              "value": 10000,
              "currency": "BRL"
            }
          },
          {
            "installments": 4,
            "installment_value": 2500,
            "interest_free": true,
            "amount": {
              "value": 10000,
              "currency": "BRL"
            }
          },
          {
            "installments": 5,
            "installment_value": 2070,
            "interest_free": false,
            "amount": {
              "value": 10349,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 349,
                    "installments": 1
                  }
                }
              },
              "currency": "BRL"
            }
          },
          {
            "installments": 6,
            "installment_value": 1754,
            "interest_free": false,
            "amount": {
              "value": 10526,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 526,
                    "installments": 2
                  }
                }
              },
              "currency": "BRL"
            }
          },
          {
            "installments": 7,
            "installment_value": 1529,
            "interest_free": false,
            "amount": {
              "value": 10706,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 706,
                    "installments": 3
                  }
                }
              },
              "currency": "BRL"
            }
          },
          {
            "installments": 8,
            "installment_value": 1361,
            "interest_free": false,
            "amount": {
              "value": 10887,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 887,
                    "installments": 4
                  }
                }
              },
              "currency": "BRL"
            }
          },
          {
            "installments": 9,
            "installment_value": 1230,
            "interest_free": false,
            "amount": {
              "value": 11071,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 1071,
                    "installments": 5
                  }
                }
              },
              "currency": "BRL"
            }
          },
          {
            "installments": 10,
            "installment_value": 1126,
            "interest_free": false,
            "amount": {
              "value": 11256,
              "fees": {
                "buyer": {
                  "interest": {
                    "total": 1256,
                    "installments": 6
                  }
                }
              },
              "currency": "BRL"
            }
          }
        ]
      }
    }
  }
}
```

Você pode notar que o objeto `payment_methods.credit_card.mastercard.installment_plans` prove um array de objetos com as informações para todas as opções de parcelamento.

> Ambiente de Teste (Sandbox)
> Os planos de parcelamento retornados no ambiente sandbox são completamente fictícios, ou seja, não há nenhuma relação com as taxas cadastradas pelo cliente.

## Crie o pedido

Após o comprador selecionar o número de parcelas e você ter os dados do cartão e do pedido, você pode criar o pedido. Para isso, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

As informações referentes ao número de parcelas e a taxa que devem ser repassados para o consumidor devem ser repassadas através do objeto `charges.amount.fees.buyer.interest`. Nele devem estar presentes as informações do número de parcelas, através do parâmetro `installments`, e o total a ser pago de taxa, através do parâmetro `total`.

Continuando com o exemplo anterior, vamos supor que o pagador tenha selecionado a opção de pagamento em 8 vezes. Nesse caso, o comprador pagaria taxa apenas referentes a 4 parcelas, já que o vendedor assumiu a taxa das outras 4 parcelas. Portanto, o parâmetro `installments` deve ter o valor `4`, indicando que o pagamento será feito em 8 parcelas com taxa em apenas 4 delas. Além disso, o parâmetro `total` deve ser definido como R$8,87, que corresponde ao valor total de taxa que o comprador pagará nesta transação. No entanto, como os valores são passados em centavos, `total`deve receber o valor `887`. Esses valores refletem a distribuição dos encargos de parcelamento acordados entre o comprador e o vendedor. A seguir são apresentados a requisição e a resposta associadas a esse exemplo.

### Request

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Authorization: Bearer TOKEN' \
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
  "qr_code": {
    "amount": {
      "value": 500
    }
  },
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
    "http://api.webhookinbox.com/i/7LMOcndm/in/"
  ],
  "charges": [
    {
      "reference_id": "referencia do pagamento",
      "description": "descricao do pagamento",
      "amount": {
        "value": 10887,
        "fees": {
          "buyer": {
            "interest": {
              "total": 887,
              "installments": 4
            }
          }
        },
        "currency": "BRL"
      },
      "payment_method": {
        "type": "CREDIT_CARD",
        "installments": 8,
        "capture": false,
        "card": {
          "number": "4111111111111111",
          "exp_month": "03",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          },
          "store": false
        }
      },
      "notification_urls": [
        "https://meusite.com/notificacoes"
      ]
    }
  ]
}
```

### Response

```json
{
  "id": "ORDE_C9963EA6-C91E-452D-8620-CA885BA22366",
  "reference_id": "ex-00001",
  "created_at": "2022-12-23T15:07:06.767-03:00",
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
      "id": "CHAR_86CB86A1-B990-435D-9B47-8DBFAD1B9999",
      "reference_id": "referencia do pagamento",
      "status": "AUTHORIZED",
      "created_at": "2022-12-23T15:07:07.068-03:00",
      "description": "descricao do pagamento",
      "amount": {
        "value": 10887,
        "currency": "BRL",
        "fees": {
          "buyer": {
            "interest": {
              "total": 887,
              "installments": 4
            }
          }
        },
        "summary": {
          "total": 10887,
          "paid": 0,
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
        "installments": 8,
        "capture": false,
        "capture_before": "2022-12-28T15:07:10-03:00",
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
          "store": false
        },
        "soft_descriptor": "MichelFariaSuziga"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_86CB86A1-B990-435D-9B47-8DBFAD1B9999",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_86CB86A1-B990-435D-9B47-8DBFAD1B9999/cancel",
          "media": "application/json",
          "type": "POST"
        },
        {
          "rel": "CHARGE.CAPTURE",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_86CB86A1-B990-435D-9B47-8DBFAD1B9999/capture",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_C9963EA6-C91E-452D-8620-CA885BA22366",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_C9963EA6-C91E-452D-8620-CA885BA22366/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}{
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
  "qr_code": {
    "amount": {
      "value": 500
    }
  },
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
    "http://api.webhookinbox.com/i/7LMOcndm/in/"
  ],
  "charges": [
    {
      "reference_id": "referencia do pagamento",
      "description": "descricao do pagamento",
      "amount": {
        "value": 10887,
        "fees": {
          "buyer": {
            "interest": {
              "total": 887,
              "installments": 4
            }
          }
        },
        "currency": "BRL"
      },
      "payment_method": {
        "type": "CREDIT_CARD",
        "installments": 8,
        "capture": false,
        "card": {
          "number": "4111111111111111",
          "exp_month": "03",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva"
          },
          "store": false
        }
      },
      "notification_urls": [
        "https://meusite.com/notificacoes"
      ]
    }
  ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.
