# Criar e pagar com autenticação 3DS do PagBank

Esse guia descreve como criar e pagar um pedido utilizando autenticação 3DS, utilizando o sistema de validação do PagBank. Essa opção cobre pagamento utilizando Cartões de Crédito e Débito.

O sistema de autenticação de cartões 3DS é um protocolo de autenticação utilizado em transações online com cartão para garantir a segurança do pagamento. Ele pode exigir a validação do titular do cartão por meio de autenticação adicional, como senha, código de verificação ou reconhecimento biométrico.

Utilize os links abaixo para navegar por esse guia:

1. [Adicione e configure o SDK PagBank](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#adicione-e-configure-o-sdk-pagbank)
2. [Autentique o cliente](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#autentique-o-cliente)

  1. [Informe os dados da compra](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#informe-os-dados-da-compra)
  2. [Autentique](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#autentique)
  3. [Identifique e trate erros](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#identifique-e-trate-erros)
3. [Crie e pague o pedido](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#crie-e-pague-o-pedido)
4. [Casos de teste](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#casos-de-teste)

## Adicione e configure o SDK PagBank

Para que você utilize o sistema de validação do Pagbank você adicionará o SDK PagBank na sua aplicação. Dessa forma, a sua página terá acesso aos métodos de autenticação disponibilizados pelo PagBank.

> Requisitos de utilização do SDK
> Antes de utilizar a SDK, devem ser geradas uma chave pública e uma sessão, respectivamente. Para obter essas informações, utilize os endpoints [Criar chave pública](/reference/criar-chave-publica) e [Criar sessão](/reference/criar-sessao-autenticacao-3ds).

Para utilizar o SDK do PagBank você deve incluir o script apresentado a seguir antes de fechar a tag `<body>` da sua página:

### html

```html
<script src="https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js"></script>
```

> IMPORTANTE
> Caso você faça algum controle de acesso por domínios na sua aplicação, é necessário permitir a execução de js e abertura de iframe de domínio "`*.cardinalcommerce.com`" e "`*.cardinaltrusted.com`"

Depois de adicionar o SDK Pagbank a sua aplicação, você irá utilizar o método `setUp`. Você deve fornecer a `session`, criada utilizando o endpoint [Criar sessão de autenticação 3DS](/reference/criar-sessao-autenticacao-3ds) e definir o ambiente que será utilizado através do parâmetro `env`, conforme demonstrado a seguir:

### javascript

```javascript
PagSeguro.setUp({
    session: 'SUA_SESSAO',
    env: 'ENV'
});
```

A tabela a seguir descreve com maiores detalhes cada parâmetro.

| Parâmetro | Descrição |
| --- | --- |
| `session` | Define a seção. Esse parâmetro indica quem é o merchant dono das interações feitas pelo SDK. **A seção é válida por no máximo 30 mínutos. Se esse tempo for superado você deve gerar uma nova seção.** |
| `env` | Define o ambiente que será utilizado. Você pode utilizar `PROD` para ambiente de produção e `SANDBOX` para ambiente sandbox. |

Caso a seção expire durante o fluxo de autenticação ou criação do pedido, uma nova seção deverá ser gerada utilizando o endpoint [Criar sessão de autenticação 3DS](/reference/criar-sessao-autenticacao-3ds). Na sequência, você deverá utilizar novamente o método `setUp` passando o novo valor para `session`.

## Autentique o cliente

Para a autenticação, você fornecerá os dados do cartão, do cliente e do dispositivo ao serviço contratado. Baseadas nessa informação o emissor do cartão irá realizar a autenticação, a qual pode ocorrer com desafio ou sem desafio.

- Sem desafio (sem atrito): o banco emissor do cartão entende que as informações fornecidas são suficientes para autenticar o consumidor
- Com desafio (com atrito): o banco emissor do cartão entende que as informações fornecidas não são suficientes para autenticar o consumidor. Assim, uma etapa adicional é necessária na qual o consumidor precisa realizar uma ação para validar a autenticidade. O recebimento de um código via SMS ou a abertura de um aplicativo são exemplos de desafios. No entanto, o tipo de desafio depende do banco emissor do cartão.

> Transações podem ser autenticadas ou não autenticadas
> A decisão de autenticar a transação é do emissor, isso significa que a sua transação pode não ser autenticada mesmo que tenha passado pelo fluxo de autenticação 3DS. Em casos de transação não autenticada, o liability em casos de chargeback de fraude não será do emissor.
> Pensando nisso o Pagbank construiu um motor de riscos que analisa de forma crítica as transações não autenticadas, buscando equilíbrio entre segurança e aprovação.

### Informe os dados da compra

Antes de realizar a autenticação é necessário organizar os dados do cliente, do pedido e configurações adicionais em um objeto. Utilize o toggle para acessar a tabela que lista todos os parâmetros e define quais são obrigatórios.

Caso você defina uma função para `beforeChallenge`, a função definida receberá um parâmetro que contém os seguintes atributos:

| Parâmetro | Descrição | Obrigatório |
| --- | --- | --- |
| `brand` | Bandeira do cartão. | Sim |
| `issuer` | Banco emissor do cartão. | Condicional (Caso exista na base de dados) |
| `open` | Função que irá chamar o desafio de autenticação. | Sim |

### Autentique

Depois de obter e organizar todas as informações necessárias para a autenticação, você irá utilizar o método `authenticate3DS`, disponibilizado pelo SDK do PagBank. Ao chamar o método `authenticate3DS`, você deve fornecer o objeto com os dados para a autenticação. A seguir você encontra um exemplo de código onde:

1. Com as informações para a autenticação é definido a variável `request`.
2. O método `setUp` é chamado.
3. O método `authenticate3DS` é utilizado para fazer a autenticação com os dados contidos em `request`.

### javascript

```javascript
const request = {
  data: {
      customer: {
          name: 'Jose da Silva',
          email: 'jose@gmail.com',
          phones: [
        {
                  country: '55',
                  area: '11',
                  number: '999999999',
                  type: 'MOBILE'
        },
        {
                  country: '55',
                  area: '11',
                  number: '999999999',
                  type: 'HOME'
        },
        {
                  country: '55',
                  area: '11',
                  number: '999999999',
                  type: 'BUSINESS'
        }
      ]
    },
      paymentMethod: {
          type: 'DEBIT_CARD',
          installments: 1,
          card: {
              number: number,
              expMonth: "02",
              expYear: "2026",
              holder: {
                  name: "Joao Silva"
        }
      }
    },
      amount: {
          value: 500,
          currency: 'BRL'
    },
      billingAddress: {
          street: 'Av. Paulista',
          number: '2073',
          complement: 'Apto 100',
          regionCode: 'SP',
          country: 'BRA',
          city: 'São Paulo',
          postalCode: '01311300'
    },
      shippingAddress: {
          street: 'Av. Paulista',
          number: '2073',
          complement: 'Apto 100',
          regionCode: 'SP',
          country: 'BRA',
          city: 'São Paulo',
          postalCode: '01311300'
    },
      dataOnly: false
  }
}

PagSeguro.setUp({
  session: document.querySelector('SUA_SESSAO').value,
  env: document.querySelector('ENV').value
});

PagSeguro.authenticate3DS(request).then( result => {
  this.logResponseToScreen(result);
  this.stopLoading();
}).catch((err) => {
  if(err instanceof PagSeguro.PagSeguroError ) {
      console.log(err);
      console.log(err.detail);
      this.stopLoading();
  }
})
```

O método `authenticate3DS` é assíncrono. Caso a Promisse associada a sua chamada seja concluída com sucesso você receberá um objeto contentendo o `status` e o `id`.

| Campo | Descrição | Obrigatório |
| --- | --- | --- |
| `status` | Define o status final do fluxo de autenticação. Pode apresentar 3 valores:<br>**AUTH_FLOW_COMPLETED**:fluxo de autenticação terminou com sucesso, a transação pode estar autenticada ou não autenticada. Deve continuar para o fluxo de criação e pagamento de pedido.<br>**AUTH_NOT_SUPPORTED**: fluxo de autenticação não foi completado. O cartão não é elegível ao programa 3DS. Para o meio de pagamento DÉBITO a transação deve ser finalizada após este retorno.<br>**CHANGE_PAYMENT_METHOD**: fluxo de autenticação foi negado pelo PagBank e outro meio de pagamento deve ser solicitado ao cliente.<br>**REQUIRE_CHALLENGE**: É um status intermediário. Elé é retornando em casos que o emissor do cartão solicita que o desafio seja realizado. Indique que o desafio deve ser exibido ao usuário. | Sim |
| `id` | Identifica a autenticação. Esse mesmo `id` deverá ser ao no fluxo de criação e pagamento do pedido posteriormente. | Condicional.<br>Retornado quando status é **AUTH_FLOW_COMPLETED**. |

Quando o `status` recebido é `AUTH_FLOW_COMPLETED`, você pode seguir para a etapa de [criação e pagamento do pedido](/reference/criar-pagar-pedido-com-3ds-validacao-pagbank#crie-e-pague-o-pedido). Você irá utilizar o valor recebido em `id` nessa próxima etapa. No entanto, se algum erro ocorreu durante o processo de autenticação, esse deverá ser analisado e tratado.

### Identifique e trate erros

Caso a Promisse associada a chamada do método `authenticate3DS` seja rejeitada, graças a ocorrência de um erro, você terá acesso ao objeto de erro. O objeto de erro irá conter o parâmetro `detail`, que também é um objeto, contendo as informações sobre a causa do problema. A tabela a seguir descreve os campos que você encontrará ao acessar o conteúdo de `detail`.

| Parâmetro | Descrição |
| --- | --- |
| `detail.httpStatus` | Indica o status HTTP retornado pelas APIs do PagBank que gerou o erro. |
| `detail.traceId` | Id único que identifica a sua requisição. Armazene essa informação para realizar o troubleshooting da sua requisição. |
| `detail.message` | Mensagem indicando o problema enfrentado. |
| `detail.errorMessages` | Lista contendo detalhes de válidações. |
| `detail.errorMessages.code` | Código da validação. |
| `detail.errorMessages.description` | Descrição da validação. |
| `detail.errorMessages.parameterName` | Parâmetro enviado que gerou o erro de validação. |

Caso você tenha problemas ou deseje testar sua implementação, recomendamos que acesse os [cenários de teste](/reference/cenarios-de-teste) descritos ao final dessa página.

## Crie e pague o pedido

Depois de obter o `id` ao finalizar o processo de autenticação e ter os dados do cartão e do pedido disponíveis, você pode criar o pedido. Para isso, você irá utilizar o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md).

Para realizar a requisição ao endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md), você precisa fornecer no corpo da requisição os dados descritos no [Objeto Order](../01-objeto-pedido.md). Os dados do pagamento devem ser adicionados ao objeto `charge`. A página [Objeto Charge](../02-objeto-charge.md) descreve em detalhes cada um dos parâmetros que devem ser incluídos.

Como você está criando e pagando um pedido usando autenticação 3DS, é necessário que no corpo da requisição sejam adicionados os dados de autenticação 3DS e os dados do cartão. Os dados do cartão devem ser adicionados ao objeto `charges.card`. O `id` do processo de autenticação 3DS deve enviado através do parâmetro `charges.authentication_method.id`. Além dos dados de autenticação, você deve definir o parâmetro `charges.authentication_method.type` com o valor `THREEDS`. Essa informação é **obrigatória** para operações com Cartão de Débito. Além disso, para que a captura da cobrança seja feita de forma automática, junto com a criação do pedido, você deve encaminhar o parâmetro `charges.payment_method.capture` com o valor `true`.

Abaixo você encontra exemplos de requisições e repostas feitas ao o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) utilizando Cartão de Crédito.

### Request (Crédito)

```bash
curl --location 'https://sandbox.api.pagseguro.com/orders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{TOKEN}}' \
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
          "id": "3DS_15CB7893-4D23-44FA-97B7-AC1BE516D418"
        }
      }
    }
  ]
}'
```

### Response (Crédito)

```json
{
  "id": "ORDE_A4AD41D9-B0E8-4CB6-AC0F-0227E5A6C05A",
  "reference_id": "ex-00001",
  "created_at": "2023-02-16T17:26:00.335-03:00",
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
      "id": "CHAR_F18B8713-39BB-4862-AE69-EB3684B8DCC2",
      "reference_id": "referencia da cobranca",
      "status": "PAID",
      "created_at": "2023-02-16T17:26:00.821-03:00",
      "paid_at": "2023-02-16T17:26:01.000-03:00",
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
          "id": "3DS_15CB7893-4D23-44FA-97B7-AC1BE516D418"
        },
        "soft_descriptor": "MyStore"
      },
      "links": [
        {
          "rel": "SELF",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_F18B8713-39BB-4862-AE69-EB3684B8DCC2",
          "media": "application/json",
          "type": "GET"
        },
        {
          "rel": "CHARGE.CANCEL",
          "href": "https://sandbox.api.pagseguro.com/charges/CHAR_F18B8713-39BB-4862-AE69-EB3684B8DCC2/cancel",
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
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_A4AD41D9-B0E8-4CB6-AC0F-0227E5A6C05A",
      "media": "application/json",
      "type": "GET"
    },
    {
      "rel": "PAY",
      "href": "https://sandbox.api.pagseguro.com/orders/ORDE_A4AD41D9-B0E8-4CB6-AC0F-0227E5A6C05A/pay",
      "media": "application/json",
      "type": "POST"
    }
  ]
}
```

Para verificar se a criação e o pagamento do pedido foram executados com sucesso, verifique os campos `charges.status` e `charges.payment_response.message` existentes no corpo da resposta.

## Casos de teste

Os dados apresentados nessa seção são fornecidos para que você possa testar diferentes comportamentos enquanto estiver utilizando o ambiente Sandbox. São fornecidos diferentes números de cartão que devem ser utilizados no campo `data.paymentMethod.card.number` ao definir o objeto com os dados da compra. Dependendo do número de cartão utilizado, você irá obter um resultado diferente no processo de autenticação com o método `authenticate3DS`. A tabela a seguir apresenta os números de cartão que você pode utilizar para realizar os testes.

| Bandeira | Dados para o teste | Resposta |
| --- | --- | --- |
| Visa | `payment_method.card.number = 4000000000002701`<br><br>`amount.value = 2701` | 3DS interno autenticado sem desafio<br><br><br>`charges.status = PAID`<br><br>`charges.threeds.status = AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001005`<br><br>`amount.value = 1005` |
| ELO | `payment_method.card.number = 6505050000001000`<br><br>`amount.value = 1000` |
| Visa | `payment_method.card.number = 4000000000002503`<br><br>`amount.value = 2503` | 3DS interno autenticado com desafio<br><br><br>`charges.status = PAID`<br><br>`charges.threeds.status = AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001096`<br><br>`amount.value = 1096` |
| ELO | `payment_method.card.number = 6505050000001091`<br><br>`amount.value = 1091` |
| Visa | `payment_method.card.number = 4000000000002925`<br><br>`amount.value = 2925` | 3DS interno não autenticado sem desafio<br><br><br>`charges.status = PAID`<br><br>`charges.threeds.status = NOT_AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001013`<br><br>`amount.value = 1013` |
| ELO | `payment_method.card.number = 6505050000001018`<br><br>`amount.value = 1018` |
| Visa | `payment_method.card.number = 4000000000002370`<br><br>`amount.value = 2370` | 3DS interno não autenticado com desafio<br><br><br>`charges.status = PAID`<br><br>`charges.threeds.status = NOT_AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001104`<br><br>`amount.value = 1104` |
| ELO | `payment_method.card.number = 6505050000001109`<br><br>`amount.value = 1109` |
| Visa | `payment_method.card.number = 4000000000002701`<br><br>`amount.value = 4001` | 3DS interno autenticado sem desafio<br><br><br>`charges.status = DECLINED`<br><br>`charges.threeds.status = AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001005`<br><br>`amount.value = 5201` |
| ELO | `payment_method.card.number = 6505050000001005`<br><br>`amount.value = 4001` |
| Visa | `payment_method.card.number = 4000000000002503`<br><br>`amount.value = 4003` | 3DS interno autenticado com desafio<br><br><br>`charges.status = DECLINED`<br><br>`charges.threeds.status = AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001096`<br><br>`amount.value = 5206` |
| ELO | `payment_method.card.number = 6505050000001091`<br><br>`amount.value = 6501` |
| Visa | `payment_method.card.number = 4000000000002925`<br><br>`amount.value = 4005` | 3DS interno não autenticado sem desafio<br><br><br>`charges.status = DECLINED`<br><br>`charges.threeds.status = NOT_AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001013`<br><br>`amount.value = 5203` |
| ELO | `payment_method.card.number = 6505050000001018`<br><br>`amount.value = 6508` |
| Visa | `payment_method.card.number = 4000000000002370`<br><br>`amount.value = 4000` | 3DS interno não autenticado com desafio<br><br><br>`charges.status = DECLINED`<br><br>`charges.threeds.status = NOT_AUTHENTICATED` |
| MASTERCARD | `payment_method.card.number = 5200000000001104`<br><br>`amount.value = 5204` |
| ELO | `payment_method.card.number = 6505050000001109`<br><br>`amount.value = 6509` |
