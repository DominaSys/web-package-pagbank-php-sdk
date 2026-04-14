# Criar pedido com cartão (facilitadores de pagamento)

Esse guia descreve como facilitadores de pagamento podem criar um pedido utilizando cartão de crédito e débito.

## Particularidades da criação de pedido para facilitadores de pagamento

Para cumprir os requisitos das bandeiras de cartão e do Banco Central, é necessário incluir informações adicionais em todas as transações enviadas. Essas informações permitem a identificação do subcomerciante pela bandeira do cartão. Tais informações de identificação serão incorporadas ao objeto `sub_merchant` ao realizar a criação do pedido.

Através do objeto `sub_merchant` na API de Pedido do PagBank, é possível criar um pedido de cobrança de pagamento para um subcomerciante. Esse método permite utilizar apenas os dados essenciais para efetuar uma cobrança de acordo com o método de pagamento escolhido. Atualmente, apenas as operações com cartão de crédito e débito são disponibilizadas.

É importante ressaltar que, para começar a operar como subcredenciador, é necessário que sua conta esteja habilitada para essa função. Para ativar essa opção, entre em contato com seu executivo comercial.

Outro ponto importante a ser destacado é que, por padrão, as transações de categorias de código de mercado (MCC) consideradas de alto risco estão bloqueadas. Caso seu negócio realize esse tipo de transação, informe ao seu executivo comercial para que suas transações sejam liberadas.

## Crie o pedido

Com os dados do cartão e do pedido, você pode criar o pedido utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Como mencionado anteriormente, você deverá incluir as informações de identificação do subcomerciante através do objeto `sub_merchant`, incorporado ao objeto `charges`. As informações que devem estar presentes em `sub_merchant`para cada objeto incluem parâmetros específicos que precisam ser enviados. Esses parâmetros devem obedecer às regras estabelecidas pelas bandeiras, conforme listado na tabela abaixo:

| Dado | Regra | Descrição | Tipo do dado |
| --- | --- | --- | --- |
| `sub_merchant.tax_id` | Deve ter 11 ou 14 dígitos. Apenas números devem ser informados.<br>- Se fornecer 11 dígitos, será validado o algoritmo conforme CPF válido.<br>Se fornecer 14 digitos, será validado o algoritmo conforme CNPJ válido. | Número do documento (CPF ou CNPJ) do lojista na plataforma do sub-adquirente. Apenas números devem ser informados (com ou sem máscara). | String (11 ou 14 caracteres) |
| `sub_merchant.name` | Não deve contem menos de 3 caracteres. | Razão social do lojista na plataforma do sub-adquirente em caso de pessoa jurídica. Em casos de pessoa física, nome completo do lojista na plataforma do sub-adquirente. | String (60 caracteres) |
| `sub_merchant.reference_id` | Não pode ser nulo. | Identificador próprio referente ao lojista atribuído na plataforma do sub-adquirente. | String (15 caracteres) |
| `sub_merchant.mcc` | Não pode ser nulo.<br>Deve ser numérico. | Código de atuação comercial do lojista na plataforma do sub-adquirente (merchant category code). Apenas números devem ser informados. Bloqueio padrão para casos de MCC de alto risco. Exemplos: 0763, 5199. | String (4 caracteres) |
| `sub_merchant.address.street` | Não pode ser nulo. | Rua do endereço. | String (1-160 caracteres) |
| `sub_merchant.address.number` | Não pode ser nulo. | Número do endereço. | String (1-20 caracteres) |
| `sub_merchant.address.complement` | Opcional.<br>Pode ser nulo. | Complemento do endereço. | String (1-40 caracteres) |
| `sub_merchant.address.locality` | Não pode ser nulo. | Bairro do endereço. | String (1-60 caracteres) |
| `sub_merchant.address.city` | Não pode ser nulo. | Cidade do endereço. | String (1-90 caracteres) |
| `sub_merchant.address.region_code` | Não pode ser nulo. | Código do Estado. | String (2 caracteres) |
| `sub_merchant.address.country` | Não pode ser nulo. | País do endereço. | String (1-50 caracteres) |
| `sub_merchant.address.postal_code` | Não pode ser nulo. | CEP do endereço. | String (8 caracteres) |
| `sub_merchant.[0]phones.country` | Não pode ser nulo. | Código de operadora do País (DDI). | Int (3 caracteres) |
| `sub_merchant.[0]phones.area` | Não pode ser nulo. | Código de operadora local (DDD). | Int (2 caracteres) |
| `sub_merchant.[0]phones.number` | Se o valor tiver 9 dígitos, ele deve começar com o dígito 9 | Número do telefone. | Int (8-9 caracteres) |
| `sub_merchant.[0]phones.type` | Não pode ser nulo. | Indica o tipo de telefone:<br>- `CELLPHONE` se for um telefone celular.<br>- `BUSINESS` se for um telefone comercial. | String (ENUM) |

Os dados do cartão utilizado no pagamento devem ser adicionados ao objeto `charges.payment_method`. Você deve identificar o meio de pagamento através do parâmetro `charges.payment_method.type` com o valor `CREDIT_CARD` ou `DEBIT_CARD`.

Abaixo você encontra um exemplo de requisição e resposta ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

### Request

```bash
curl --location --request POST 'https: //sandbox.api.pagseguro.com/orders' \
--header 'Authorization: Bearer {
  {token
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
      "reference_id": "MY-ID-123",
      "description": "Motivo de pagamento",
      "amount": {
        "value": 1000,
        "currency": "BRL"
      },
      "payment_method": {
        "type": "CREDIT_CARD",
        "installments": 1,
        "capture": true,
        "soft_descriptor": "Loja do meu teste",
        "card": {
          "number": "4111111111111111",
          "exp_month": "03",
          "exp_year": "2026",
          "security_code": "123",
          "holder": {
            "name": "Jose da Silva",
            "tax_id": "65544332211"
          }
        }
      },
      "sub_merchant": {
        "reference_id": "MY-ID",
        "name": "Razão Social / Nome completo",
        "tax_id": "42167200803",
        "mcc": "155",
        "address": {
          "country": "BRA",
          "region_code": "SP",
          "city": "Sao Paulo",
          "postal_code": "01452002",
          "street": "Avenida Brigadeiro Faria Lima",
          "number": "1384",
          "locality": "Pinheiros",
          "complement": "Apto 16"
        },
        "phones": [
          {
            "country": "55",
            "area": "11",
            "number": "98877887788",
            "type": "MOBILE"
          }
        ]
      },
      "notification_urls": [
        "https://yourserver.com/nas_ecommerce/277be731-3b7c-4dac-8c4e-4c3f4a1fdc46/"
      ]
    }
  ]
}'
```

### Response

```json
{
  "id": "ORDE_3F3EA44E-6A82-4D52-BC23-94DF2096A1FD",
  "reference_id": "ex-00001",
  "created_at": "2023-02-08T17:44:34.959-03:00",
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
      "id": "CHAR_1F7CAB6E-6311-4225-82F8-77355CCA1E30",
      "reference_id": "MY-ID-123",
      "status": "PAID",
      "created_at": "2023-02-08T17:44:35.385-03:00",
      "paid_at": "2023-02-08T17:44:35.000-03:00",
      "description": "Motivo de pagamento",
      "amount": {
        "value": 1000,
        "currency": "BRL",
        "summary": {
          "total": 1000,
          "paid": 1000,
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
        "soft_descriptor": "Lojadomeuteste"
      },
      "sub_merchant": {
        "reference_id": "MY-ID",
        "name": "Razão Social / Nome completo",
        "tax_id": "42167200803",
        "mcc": "155",
        "address": {
          "city": "Sao Paulo",
          "postal_code": "01452002",
          "street": "Avenida Brigadeiro Faria Lima",
          "number": "1384",
          "locality": "Pinheiros",
          "country": "BRA",
          "region_code": "SP",
          "complement": "Apto 16"
        },
        "phones": [
          {
            "country": "55",
            "area": "11",
            "number": "98877887788",
            "type": "MOBILE"
          }
        ]
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_1F7CAB6E-6311-4225-82F8-77355CCA1E30",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_1F7CAB6E-6311-4225-82F8-77355CCA1E30/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_3F3EA44E-6A82-4D52-BC23-94DF2096A1FD",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_3F3EA44E-6A82-4D52-BC23-94DF2096A1FD/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.
