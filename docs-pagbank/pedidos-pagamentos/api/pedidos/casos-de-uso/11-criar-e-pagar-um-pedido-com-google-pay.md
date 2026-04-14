# Criar e pagar um pedido com Google Pay™

Nesta página descreveremos o fluxo de criação e pagamento de um pedido com Google Pay™.

> Integre com Google Pay
> Antes de poder criar um pedido é necessário haver integrado com o Google Pay™ para poder realizar chamadas na API. Acesse a página de [Como integrar com Google Pay](13-como-integrar-o-sdk-do-google-pay.md) para saber como.

## Criação do pedido

De posse dos dados do cartão e do pedido, você pode criar o pedido utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](objeto-order). Os dados do pagamento devem ser adicionados ao objeto charge, a página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Para transações com o Google Pay™, sempre será obrigatório o envio do objeto `wallet`, com os campos `type` e `key`preenchidos com valores básicos.

| Parâmetro | Descrição |
| --- | --- |
| charges.card.wallet.type | Tipo de wallet, para esse cenário deverá ser enviado o valor GOOGLE_PAY. |
| charges.card.wallet.key | Credencial de pagamento devolvida pelo Google. |

O bloco de código abaixo mostra um Request e Response de uma transação, enviando o objeto `wallet`.

### Request

```json
{
    "reference_id": "ex-00001",
    "customer": {
        "name": "Jose da Silva",
        "email": "jose@silva.com",
        "tax_id": "123456789",
        "phones": [
            {
                "country": "55",
                "area": "11",
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
            "reference_id": "ex-00001",
            "description": "motivo da cobranca",
            "amount": {
                "value": 500,
                "currency": "BRL"
            },
            "payment_method": {
                "type": "CREDIT_CARD",
                "card": {
                    "wallet": {
                        "type": "GOOGLE_PAY",
                        "key": "{\"signature\"…}"
                    }
                }
            }
        }
    ]
}
```

### Response

```json
{
   "reference_id":"ex-00001",
   "created_at":"2025-08-22T11:41:37.613-03:00",
   "customer":{
      "name":"Jose da Silva",
      "email":"jose@silva.com",
      "tax_id":"123456789",
      "phones":[
         {
            "type":"MOBILE",
            "country":"55",
            "area":"11",
            "number":"999999998"
         }
      ]
   },
   "items":[
      {
         "reference_id":"referencia do item",
         "name":"nome do item",
         "quantity":1,
         "unit_amount":500
      }
   ],
   "shipping":{
      "address":{
         "street":"Avenida Brigadeiro Faria Lima",
         "number":"1384",
         "complement":"apto 12",
         "locality":"Pinheiros",
         "city":"São Paulo",
         "region_code":"SP",
         "country":"BRA",
         "postal_code":"01452002"
      }
   },
   "charges":[
      {
         "reference_id":"ex-00001",
         "status":"PAID",
         "created_at":"2025-08-22T11:41:37.613-03:00",
         "paid_at":"2025-08-22T11:41:37.613-03:00",
         "description":"motivo da cobranca",
         "amount":{
            "value":500,
            "currency":"BRL",
            "summary":{
               "total":500,
               "paid":500,
               "refunded":0
            }
         },
         "payment_response":{
            "code":"20000",
            "message":"SUCESSO",
            "reference":"431214519720",
            "raw_data":{
               "authorization_code":"004581",
               "nsu":"431214519720",
               "reason_code":"00",
               "security_level_indicator":"05"
            }
         },
         "payment_method":{
            "type":"CREDIT_CARD",
            "installments":1,
            "capture":true,
            "card":{
               "brand":"visa",
               "first_digits":"467092",
               "last_digits":"9948",
               "exp_month":"12",
               "exp_year":"2031",
               "wallet":{
                  "type":"GOOGLE_PAY"
               }
            },
            "soft_descriptor":"Cobranca"
         }
      }
   ],
   "notification_urls":[
      "https://meusite.com/notificacoes"
   ]
}
```

> Liability Shift
> Em transações com Google Pay, pode ocorrer a transferência de responsabilidade (liability shift) quando o campo `security_level_indicator` estiver configurado com os seguintes valores:
> **Visa** `05` – Transação autenticada
> **Mastercard** `242` – Transação tokenizada e autenticada

## Cartões de teste

Para a realização do fluxo de testes é necessário que o e-mail da conta de desenvolvedor do Google esteja cadastrado no [grupo](https://groups.google.com/my-groups).

Assim será possível utilizar os cartões disponibilizados pelo Pagbank, passando os seguintes parâmetros abaixo no `tokenizationSpecification`:

- `gateway`: pagbank
- `gatewayMerchantId`: ID da conta (pode ser solicitado à equipe do Pagbank).

Uma vez cadastrado e passados os parâmetros, será disponibilizada a listagem de cartões de teste para utilizar no ambiente de sandbox. A tabela a seguir lista os cartões disponíveis:

| Situação | Dados do cartão |
| --- | --- |
| Transação aprovada Mastercard | Número do cartão: 5240082975622454<br><br>Data de Expiração: 12/2026 |
| Transação negada Mastercard | Número do cartão: 5530062640663264<br><br>Data de Expiração: 12/2026 |
| Transação aprovada Visa | Número do cartão: 4539620659922097<br><br>Data de Expiração: 12/2026 |
| Transação negada Visa | Número do cartão: 4929291898380766<br><br>Data de Expiração: 12/2026 |
