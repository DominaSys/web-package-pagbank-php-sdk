# Criar e pagar um pedido com Apple Pay

Este guia detalha o processo para integrar o Apple Pay com o PagBank, permitindo que seus clientes utilizem este método de pagamento.

A integração consiste em três etapas principais:

1. [Cadastrar e gerar o arquivo `.cer` na Apple](#1-cadastro-e-gera%C3%A7%C3%A3o-do-arquivo-cer-na-apple)
2. [Desenvolver a integração com Apple Pay](#2-desenvolvimento-da-integra%C3%A7%C3%A3o)
3. [Criar pedido com o Apple Pay](#3-criar-pedido-com-o-apple-pay)

Esses passos são detalhados nas seções seguintes desta página.

## 1. Cadastro e geração do arquivo .cer na Apple

O primeiro passo do processo de integração é gerar o arquivo `.cer` através do sistema da Apple. Para isso, você deve ter uma conta Apple para desenvolvedores devidamente cadastrada e habilitada.

### 1.1. Obtenha o arquivo .csr

Para requisitar o arquivo `.cer` junto a Apple, antes você precisa obter o arquivo `.csr` do PagBank. Para isso, siga os passos abaixo:

1. Realize uma requisição para o endpoint apresentado no bloco de código abaixo para obter o arquivo `.csr` fornecido pelo PagBank. Para testes, utilize a URL do ambiente Sandbox. Nessa requisição, utilize o método `POST` e informe o seu token de autorização. O bloco de código abaixo apresenta um exemplo de requisição.

> Token de autenticação
> Para requisitar o arquivo `.csr`, é necessário fornecer o token de autenticação apropriado.
> - **Ambiente Sandbox**: Utilize o token de autenticação deste ambiente para realizar testes.
> - **Ambiente de Produção**: Utilize o token de autenticação correspondente para obter o arquivo `.csr` definitivo.
> Para mais detalhes sobre como gerar e utilizar tokens de autenticação, consulte a página [Token de autenticação](/docs/token-de-autenticacao).

### URL do endpoint (Sandbox)

```
https://sandbox.api.pagseguro.com/wallets/apple-pay/csr
```

### URL do endpoint (Produção)

```
https://api.pagseguro.com/wallets/apple-pay/csr
```

### Exemplo de requisição

```bash
curl --location --request POST 'https://api.pagseguro.com/wallets/apple-pay/csr' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{token}}'
```

1. O PagBank fornecerá na resposta da requisição o conteúdo do arquivo `.csr`. Salve o conteúdo retornado como um arquivo com a extensão `.csr`.

### 1.2. Baixe o arquivo .cer

Após ter o arquivo `.csr` obtido junto ao PagBank, você pode realizar o processe para obter o arquivo `.cer`. Para isso, realize os passos a seguir:

1. Acesse o [portal da Apple](https://developer.apple.com/account/resources) com sua conta de desenvolvedor.
2. Acesse **Merchants** e crie ou selecione um merchant para sua integração.

1. Acesse o menu de certificados e solicite um novo certificado para o merchant.

1. Envie o arquivo `.csr` gerado no Passo 2.
2. Baixe o arquivo `.cer` ao final do processo.

### 1.3. Envie o arquivo .cer para o PagBank

Após baixar o arquivo `.cer` do portal da Apple, você deve enviar o arquivo para o PagBank. Para isso, você deve realizar uma requisição para o endpoint apresentado a seguir. Caso esteja testando a sua aplicação, utilize o endpoint do ambiente Sandbox.

### URL do endpoint (Sandbox)

```
https://sandbox.api.pagseguro.com/wallets/apple-pay/cer
```

### URL do endpoint (Produção)

```
https://api.pagseguro.com/wallets/apple-pay/cer
```

O bloco de código abaixo demonstra como enviar o arquivo ao endpoint do PagBank utilizando `cURL`. Substitua `/caminho/para/o/arquivo.cer` pelo caminho completo onde o arquivo `.cer` está salvo no seu sistema:

### Exemplo de requisição

```bash
curl --location --request POST 'https://api.pagseguro.com/wallets/apple-pay/cer' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{token}}' \
--form 'cer=@"/caminho/para/o/arquivo.cer"'
```

Após este processo, o certificado estará vinculado às APIs do PagBank e pronto para uso.

## 2. Desenvolvimento da integração

A integração com os serviços do Apple Pay deve ser desenvolvida seguindo a[documentação oficial da Apple](https://developer.apple.com/documentation/apple_pay_on_the_web).

> Bandeiras suportadas
> Atualmente, o PagBank está preparado para processar pagamentos que utilizam cartões com as bandeiras Visa e Mastercard.

Para realizar os testes da sua integração, o Apple Pay fornece um ambiente Sandbox. Acesse a [documentação do Apple Pay](https://developer.apple.com/apple-pay/sandbox-testing/) para mais informações.

## 3. Criar pedido com o Apple Pay

Da integração com o Apple Pay, você poderá acessar o token do cartão do usuário, permitindo que você realize um pagamento. Para criar um pagamento, você deve:

- Utilizará o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) disponibilizado pelo PagBank.
- Os dados do pagamento devem ser adicionados ao objeto `charge`, no corpo da requisição.
- Operações com Apple Pay utilizam cartão como método de pagamento, portanto, você deve enviar `charges.payment_method.type = CREDIT_CARD`.
- Informe que você irá utilizar a wallet do Apple Pay e adicione a `key` obtida no campo data (`token.paymentData`) da resposta da integração com o Apple Pay:

| Parâmetro | Descrição |
| --- | --- |
| charges.payment_method.card.wallet.type | Tipo de wallet. Para esse cenário, use o valor APPLE_PAY. |
| charges.payment_method.card.wallet.key | Credencial de pagamento fornecida pela Apple. A credencial é extraída do campo `token.paymentData`. Esse dado é gerado após a interação com o botão do Apple Pay e a seleção do cartão na interface de pagamento da Apple. Para mais detalhes sobre o fluxo e a estrutura do payload retornado, consulte a [documentação oficial da Apple](https://developer.apple.com/documentation/apple_pay_on_the_web/applepaypaymenttoken/1916115-paymentdata/) . |

A seguir, você encontra um exemplo de requisição e resposta para criação de um pagamento utilizando o endpoint [Criar pedido](../gerencie-pedidos/01-criar-pedido.md) e o cartão proveniente do Apple Pay como meio de pagamento.

### Requisição

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
                        "type": "APPLE_PAY",
                        "key": "{…}"
                    }
                }
            }
        }
    ]
}
```

### Resposta

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
               " security_level_indicator ":"05"
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
                  "type":"APPLE_PAY"
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
> Em transações com Apple Pay, pode ocorrer a transferência de responsabilidade (liability shift) quando o campo `security_level_indicator` estiver configurado com os seguintes valores:
> **Visa** `05`: Transação autenticada
> **Mastercard** `242` – Transação tokenizada e autenticada
