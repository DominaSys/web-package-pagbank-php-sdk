# Criar pedido

Esse guia descreve como criar um pedido utilizando a API de pedidos do PagBank. Essa é a abordagem mais simples para se criar um pedido apresentada nos [Casos de uso](/docs/pedidos-e-pagamentos-order#casos-de-uso) da API.

## Crie o pedido

Com os dados do pedido em mãos você pode criá-lo utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). No entanto, como esse processo não envolve o pagamento, você não deve enviar o objeto `charge` dentro do objeto Order.

Abaixo você encontra um exemplo de requisição e resposta ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

### Request

```bash
curl --request POST \
     --url https://sandbox.api.pagseguro.com/orders \
     --header 'Authorization: Bearer <token>' \
     --header 'accept: application/json' \
     --header 'content-type: application/json' \
     --data '
{
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
  ]
}
'
```

### Response

```
{
  "id": "ORDE_F87334AC-BB8B-42E2-AA85-8579F70AA328",
  "reference_id": "ex-00001",
  "created_at": "2020-11-21T23:23:22.69-03:00",
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
  "items": [
    {
      "reference_id": "referencia do item",
      "name": "nome do item",
      "quantity": 1,
      "unit_amount": 500
    }
  ],
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
  "charges": [],
  "qr_codes": [],
  "links": []
}
```

Para verificar se o seu pedido foi criado de forma adequada, a resposta deve conter o parâmetro `id`, utilizado para identificar o pedido.

Depois de criar o pedido e ter o valor do `id` de identificação do pedido, você pode utilizar o endpoint [Pagar pedido](../gerencie-pedidos/06-pagar-pedido.md) para gerar a cobrança. O [Objeto Charge](../02-objeto-charge.md) com os dados do meio de pagamento e do valor da cobrança serão eviados ao PagBank através dessa requisição.
