# Criar e pagar pedido com Boleto

Esse guia descreve como criar e pagar um pedido utilizando Boleto como meio de pagamento. Esse método de pagamento impõe um valor mínimo de R$0,20 para cada transação.

Para a criação e pagamento do pedido utilizando Boleto, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). Alguns parâmetros são obrigatórios quando criando um pedido utilizando Boleto. Uma lista é apresentada na tabela a seguir:

| Parâmetro | Descrição |
| --- | --- |
| `charges.payment_method.type` | Define o tipo do método de pagamento como Boleto. **Você deve, obrigatóriamente, atribuir o valor `BOLETO`.** |
| `charges.payment_method.boleto.due_date` | Data de vencimento do Boleto. |
| `charges.payment_method.boleto.instruction_lines.line_1` | Instruções para o processamento do Boleto. |
| `charges.payment_method.boleto.instruction_lines.line_2` | Instruções para o processamento do Boleto. |
| `charges.payment_method.boleto.holder.name` | Nome do cliente realizando o pedido. |
| `charges.payment_method.boleto.holder.tax_id` | Documento do cliente. |
| `charges.payment_method.boleto.holder.email` | E-mail do cliente. |
| `charges.payment_method.boleto.holder.address` | Objeto com os dados do endereço do cliente. |
| `charges.payment_method.boleto.days_until_expiration` | Data de expiração do Boleto. |
| `charges.payment_method.boleto.template` | Tipo de boleto (`COBRANCA` ou `PROPOSTA`). |

Ao criar um pedido com Boleto usando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), a resposta da API retorna os dados necessários para o pagamento em dois formatos:

1. Linha Digitável (Código de Barras)
2. Link para Impressão

Você pode acessar esses dados nos seguintes parâmetros do objeto de resposta:

- **Para a Linha Digitável:**

  - `charges.payment_method.boleto.barcode`: Código de barras numérico.
  - `charges.payment_method.boleto.formatted_barcode`: Código de barras formatado (com pontuação).
- **Para o Link de Impressão:**

  - `charges.links`: Este parâmetro é um array de objetos. Cada objeto contém um link para uma versão do boleto, como PDF ou imagem.

Após a criação do pedido com boleto, o parâmetro `charges.status` assumirá o valor `WAITING`. Este status indica que o sistema aguarda a confirmação do pagamento pelo usuário.

Após a criação do pedido com boleto, o parâmetro `charges.status` asumirá um dos seguintes valores:

- `WAITING`: Indica que o sistema aguarda a confirmação do pagamento pelo usuário.
- `DECLINED`: Indica que a transação foi negada pelo PagBank.

> Análise de Risco
> Todos os boletos passam por análise de risco.

O bloco de código abaixo apresenta exemplos de request e response.

### Request

```bash
curl --request POST \
  --url https://sandbox.api.pagseguro.com/orders \
  --header 'Authorization: Bearer <token>' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --data '{
    "reference_id": "my_order_{{$randomInt}}",
    "customer": {
      "name": "Customer Teste Samara",
      "email": "jose@silva.com",
      "tax_id": "10642432074",
      "phones": [
        {
          "country": "55",
          "area": "34",
          "number": "999999998",
          "type": "MOBILE"
        }
      ]
    },
    "items": [
      {
        "reference_id": "referencia do item",
        "name": "nome do item",
        "quantity": 1,
        "unit_amount": 50
      }
    ],
    "shipping": {
      "address": {
        "street": "Avenida Brigadeiro Faria Lima",
        "number": "1384",
        "complement": "AP 77",
        "locality": "Pinheiros",
        "city": "Sao Paulo",
        "region_code": "SP",
        "country": "BRA",
        "postal_code": "01452002"
      }
    },
    "charges": [
      {
        "reference_id": "de55d23d-4ec3-4f53-9479-7fd1789184c7",
        "description": "Boleto de fatura",
        "amount": {
          "value": 34343,
          "currency": "BRL"
        },
        "payment_instructions": {
          "fine": {
            "date": "2025-10-21",
            "value": 200
          },
          "interest": {
            "date": "2025-10-21",
            "value": 200
          },
          "discounts": [
            {
              "due_date": "2025-10-20",
              "value": 500
            }
          ]
        },
        "payment_method": {
          "type": "BOLETO",
          "boleto": {
            "template": "COBRANCA",
            "due_date": "2025-10-20",
            "days_until_expiration": "45",
            "holder": {
              "name": "Joao Silva",
              "tax_id": "07578643096",
              "email": "email@gmail.com",
              "address": {
                "street": "RUA DOUTOR ANTONIO BENTO",
                "number": "123",
                "postal_code": "04750001",
                "locality": "Santo Amaro",
                "city": "São Paulo",
                "region": "SP",
                "region_code": "SP",
                "country": "Brasil"
              }
            },
            "instruction_lines": {
              "line_1": "Pagamento ate a data de vencimento",
              "line_2": "teste linha 2"
            }
          }
        }
      }
    ]
  }'
```

### Response

```json
{
   "id":"ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063",
   "reference_id":"my_order_28",
   "created_at":"2025-09-16T11:29:44.145-03:00",
   "customer":{
      "name":"Customer Teste Samara",
      "email":"jose@silva.com",
      "tax_id":"10642432074",
      "phones":[
         {
            "type":"MOBILE",
            "country":"55",
            "area":"34",
            "number":"999999998"
         }
      ]
   },
   "items":[
      {
         "reference_id":"referencia do item",
         "name":"nome do item",
         "quantity":1,
         "unit_amount":50
      }
   ],
   "shipping":{
      "address":{
         "street":"Avenida Brigadeiro Faria Lima",
         "number":"1384",
         "complement":"AP 77",
         "locality":"Pinheiros",
         "city":"Sao Paulo",
         "region_code":"SP",
         "country":"BRA",
         "postal_code":"01452002"
      }
   },
   "charges":[
      {
         "id":"CHAR_60269EC9-9723-444D-AA49-C42813AF9613",
         "reference_id":"de55d23d-4ec3-4f53-9479-7fd1789184c7",
         "status":"WAITING",
         "created_at":"2025-09-16T11:29:44.356-03:00",
         "description":"Boleto de fatura",
         "amount":{
            "value":34343,
            "currency":"BRL",
            "summary":{
               "total":34343,
               "paid":0,
               "refunded":0
            }
         },
         "payment_instructions":{
            "fine":{
               "date":"2025-10-21",
               "value":200
            },
            "interest":{
               "date":"2025-10-21",
               "value":200
            },
            "discounts":[
               {
                  "due_date":"2025-10-20",
                  "value":500
               }
            ]
         },
         "payment_response":{
            "code":"20000",
            "message":"SUCESSO"
         },
         "payment_method":{
            "type":"BOLETO",
            "boleto":{
               "id":"6DD9494E-463C-4689-9436-AA3ADA873214",
               "barcode":"08197081080010000001701152240436612400000034343",
               "formatted_barcode":"08197.08108 00100.000017 01152.240436 6 12400000034343",
               "due_date":"2025-10-20",
               "instruction_lines":{
                  "line_1":"Pagamento ate a data de vencimento",
                  "line_2":"teste linha 2"
               },
               "holder":{
                  "name":"Joao Silva",
                  "tax_id":"07578643096",
                  "email":"email@gmail.com",
                  "address":{
                     "region":"SP",
                     "city":"São Paulo",
                     "postal_code":"04750001",
                     "street":"RUA DOUTOR ANTONIO BENTO",
                     "number":"123",
                     "locality":"Santo Amaro",
                     "country":"Brasil",
                     "region_code":"SP"
                  }
               },
               "days_until_expiration":45
            }
         },
         "links":[
            {
               "rel":"SELF",
               "href":"https://boleto.pagseguro.com.br/6dd9494e-463c-4689-9436-aa3ada873214.pdf",
               "media":"application/pdf",
               "type":"GET"
            },
            {
               "rel":"SELF",
               "href":"https://boleto.pagseguro.com.br/6dd9494e-463c-4689-9436-aa3ada873214.png",
               "media":"image/png",
               "type":"GET"
            },
            {
               "rel":"SELF",
               "href":"https://api.pagseguro.com/charges/CHAR_60269EC9-9723-444D-AA49-C42813AF9613",
               "media":"application/json",
               "type":"GET"
            }
         ]
      }
   ],
   "notification_urls":[

   ],
   "links":[
      {
         "rel":"SELF",
         "href":"https://api.pagseguro.com/orders/ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063",
         "media":"application/json",
         "type":"GET"
      },
      {
         "rel":"PAY",
         "href":"https://api.pagseguro.com/orders/ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063/pay",
         "media":"application/json",
         "type":"POST"
      }
   ]
}
```

### Response (DECLINED)

```json
{
  "id": "ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063",
  "reference_id": "my_order_28",
  "created_at": "2025-09-16T11:29:44.145-03:00",
  "customer": {
    "name": "José Silva",
    "email": "jose@silva.com",
    "tax_id": "10642432074",
    "phones": [
      {
        "type": "MOBILE",
        "country": "55",
        "area": "34",
        "number": "999999998"
      }
    ]
  },
  "items": [
    {
      "reference_id": "referencia do item",
      "name": "nome do item",
      "quantity": 1,
      "unit_amount": 50
    }
  ],
  "shipping": {
    "address": {
      "street": "Avenida Brigadeiro Faria Lima",
      "number": "1384",
      "complement": "AP 77",
      "locality": "Pinheiros",
      "city": "Sao Paulo",
      "region_code": "SP",
      "country": "BRA",
      "postal_code": "01452002"
    }
  },
  "charges": [
    {
      "id": "CHAR_60269EC9-9723-444D-AA49-C42813AF9613",
      "reference_id": "de55d23d-4ec3-4f53-9479-7fd1789184c7",
      "status": "DECLINED",
      "created_at": "2025-09-16T11:29:44.356-03:00",
      "description": "Boleto de fatura",
      "amount": {
        "value": 34343,
        "currency": "BRL",
        "summary": {
          "total": 34343,
          "paid": 0,
          "refunded": 0
        }
      },
      "payment_instructions": {
        "fine": {
          "date": "2025-10-21",
          "value": 200
        },
        "interest": {
          "date": "2025-10-21",
          "value": 200
        },
        "discounts": [
          {
            "due_date": "2025-10-20",
            "value": 500
          }
        ]
      },
      "payment_response": {
        "code": "10000",
        "message": "NAO AUTORIZADO PELO PAGSEGURO"
      },
      "payment_method": {
        "type": "BOLETO",
        "due_date": "2025-10-20",
        "instruction_lines": {
          "line_1": "Pagamento ate a data de vencimento",
          "line_2": "teste linha 2"
        },
        "holder": {
          "name": "Joao Silva",
          "tax_id": "07578643096",
          "email": "email@gmail.com",
          "address": {
            "region": "SP",
            "city": "São Paulo",
            "postal_code": "04750001",
            "street": "RUA DOUTOR ANTONIO BENTO",
            "number": "123",
            "locality": "Santo Amaro",
            "country": "Brasil",
            "region_code": "SP"
          }
        },
        "days_until_expiration": 45
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://api.pagseguro.com/charges/CHAR_60269EC9-9723-444D-AA49-C42813AF9613",
          "media": "application/json",
          "type": "GET"
        }
      ]
    }
  ],
  "notification_urls": [],
  "links": [
    {
      "rel": "SELF",
      "href": "https://api.pagseguro.com/orders/ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://api.pagseguro.com/orders/ORDE_2FAC517E-0969-4D10-BF5F-601CBA2D1063/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

## Tipos de Boleto

O PagBank permite a emissão de dois tipos de boleto:

- Proposta
- Cobrança

O modelo Proposta é o padrão, sem instruções de cobrança. O modelo Cobrança permite configurar juros, multas e descontos, sendo ideal para controle de inadimplência ou para incentivar pagamentos antecipados.

| Tipo | Valor do `template` | Permite Juros/Multas/Desconto |
| --- | --- | --- |
| Proposta | `PROPOSTA` | ❌ Não |
| Cobrança | `COBRANCA` | ✅ Sim |

Para utilizar o modelo de cobrança, siga estes passos:

1. Defina o campo `template` como `COBRANCA`.
2. Inclua o objeto `payment_instructions` no payload da requisição com as regras desejadas.

Este exemplo demonstra a configuração de multa, juros e desconto no objeto `payment_instructions`.

### json

```json
{
  "payment_instructions": {
    "fine": {
      "date": "2025-10-21",
      "value": 200
    },
    "interest": {
      "date": "2025-10-21",
      "value": 200
    },
    "discounts": [
      {
        "due_date": "2025-10-20",
        "value": 500
      }
    ]
  }
}
```

> Se preferir, é possível emitir um boleto com o `template` `COBRANCA` sem incluir o objeto `payment_instructions`. Neste caso, ele funcionará como um boleto simples, mas com a estrutura de cobrança.

### Regras e Validação

Ao definir as regras de pagamento. O campo `value` deve ser enviado como um número inteiro. Este valor representa o percentual multiplicado por 100:

- Para 2,00%, envie `value: 200`
- Para 5,00%, envie `value: 500`
- Para 0,01%, envie `value: 1`

Os limites para os valores de cada um dos parâmetros são apresentados na tabela:

| Parâmetro | Descrição | Limite Percentual | Valor (`value`) Inteiro |
| --- | --- | --- | --- |
| `fine` | Multa | 0,01% a 99,99% | `1` a `9999` |
| `interest` | Juros | 0,01% a 59,99% | `1` a `5999` |
| `discounts` | Desconto | 0,01% a 99,99% | `1` a `9999` |

É importante notas as seguintes regras de uso:

- **Multa e Juros (`fine`, `interest`):** Só podem ser aplicados *após* a data de vencimento (o `date` deve ser D+1 do vencimento).
- **Descontos (`discounts`):** A data de aplicação (`due_date`) deve ser *igual ou anterior* à data de vencimento.

> O cálculo dos percentuais de multa e juros é realizado pela instituição financeira emissora do boleto, não pelo PagBank.

> Tratamento de Erro
> Em caso de erro de validação nestes campos (ex: valor fora do limite), a API retornará o erro `40002 - invalid_parameter`.

## Orientação para teste no ambiente sandbox

Ao criar um pagamento com Boleto no ambiente sandbox, é possível simular dois status distintos. O primeiro é relacionado com o cenário onde o Boleto é pago já no segundo, o Boleto não é pago e acaba expirando. Essas duas situações são cobertas pelo [Simulador](/reference/simulador).
