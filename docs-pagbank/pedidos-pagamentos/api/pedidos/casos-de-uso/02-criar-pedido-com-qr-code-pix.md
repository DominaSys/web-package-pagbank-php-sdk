# Criar pedido com QR Code (PIX)

Esse guia descreve como criar um pedido com QR Code. No PagBank, todo pedido criado com QR Code suporta apenas o PIX como meio de pagamento.

Para que o QR Code aceite o pagamento PIX, é necessário que você possua pelo menos uma chave PIX de endereçamento ativa associada à sua conta PagBank. Se você possuir várias chaves de endereçamento cadastradas, será dada prioridade à utilização de uma chave de endereçamento aleatória.

É importante destacar que o QR Code gerado pode ser utilizado para um único pagamento. Isso significa que após uma transação bem-sucedida realizada usando o QR Code, ele não poderá ser utilizado para receber outros pagamentos. Atualmente, o sistema PagBank suporta a criação de apenas um QR Code por pedido.

## Crie o pedido com QR Code

Para a criação do pedido utilizando QR Code, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). Ao contrário dos pedidos com cartão ou Boleto, ao utilizar QR Code você não precisar fornecer na requisição o objeto `charges`. Os dados relacionados ao valor do pedido e data de expiração do QR Code usando os parâmetros `qr_codes.amount.value` e `qr_codes.expiration_date`, respectivamente. Por padrão, o QR Code gerado tem validade de 24 horas caso o parâmetro `qr_codes.expiration_date` não seja definido na requisição.

Ao adicionar informações ao objeto `qr_codes` na requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), o QR code será gerado automaticamente. Para realizar o pagamento, o usuário poderá utilizar o app PagBank ou aplicativo de outras instituições que suportam o PIX.

Abaixo você encontra um exemplo de requisição e reposta feitas ao o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) para criar um pedido com QR Code.

### Request

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
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
      "name": "nome do item",
      "quantity": 1,
      "unit_amount": 500
    }
  ],
  "qr_codes": [
    {
      "amount": {
        "value": 500
      },
      "expiration_date": "2021-08-29T20:15:59-03:00",
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
}'
```

### Response

```json
{
  "id": "ORDE_9BBD677F-863E-4D46-BAAF-2EECEE49FF31",
  "reference_id": "ex-00001",
  "created_at": "2021-01-19T09:30:12.197-03:00",
  "customer": {
    "name": "José da Silva",
    "email": "jose@email.com",
    "tax_id": "12345678901",
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
      "quantity": "1",
      "unit_amount": "100"
    }
  ],
  "amount": {
    "currency": "BRL",
    "additional": 100,
    "discount": 100
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
  "qr_codes": [
    {
      "id": "QRCO_9E13BFE1-35C3-4DFD-B499-9B110AC0E1BA",
      "expiration_date": "2021-08-29T20:15:59-03:00",
      "amount": {
        "value": 100
      },
      "text": "00020101021226830014br.gov.bcb.pix2561api.pagseguro.com/pix/v2/9E13BFE1-35C3-4DFD-B499-9B110AC0E1BA27600016BR.COM.PAGSEGURO01369E13BFE1-35C3-4DFD-B499-9B110AC0E1BA52045697530398654041.005802BR5925Leticia Oliveira Porto La6007Barueri62070503***6304658F",
      "links": [
        {
          "rel": "QRCODE.PNG",
          "href": "https://api.pagseguro.com/qrcode/QRCO_9E13BFE1-35C3-4DFD-B499-9B110AC0E1BA/png",
          "media": "image/png",
          "type": "GET"
        },
        {
          "rel": "QRCODE.BASE64",
          "href": "https://api.pagseguro.com/qrcode/QRCO_9E13BFE1-35C3-4DFD-B499-9B110AC0E1BA/base64",
          "media": "text/plain",
          "type": "GET"
        }
      ]
    }
  ],
  "charges": [],
  "links": [
    {
      "rel": "SELF",
      "href": "https://api.pagseguro.com/orders/ORDE_9BBD677F-863E-4D46-BAAF-2EECEE49FF31",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://api.pagseguro.com/orders/ORDE_9BBD677F-863E-4D46-BAAF-2EECEE49FF31/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

Ao criar um pedido com QR Code, o PagBank disponibiliza duas formas para que o usuário possa consumi-lo, através do código de texto e imagem. Essas informações estão presentes na resposta do endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md). Para acessá-las utilize o parâmetro `qr_codes.links` que disponibiliza dois objetos. O objeto com parâmetro `media = text/plain` fornece a URL para acessar o código de texto vinculado ao QR Code. Já o objeto com parâmetro `media = image/png` fornece a URL para acessar a imagem do QR Code.
