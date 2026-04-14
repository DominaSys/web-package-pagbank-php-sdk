# Como integrar o SDK do Google Pay™

> Integração
> Para realizar a integração com a API do Google Pay™ você também pode consultar detalhes no portal do [Google](https://developers.google.com/pay/api/web/overview?hl=pt-br).

O processo de integração do SDK do Google Pay é dividido em 4 etapas.

1. Carregar o script principal (`pay.js`)
2. Chamar a API `IsReadytoPay`
3. Chamar a API `createButton`
4. Chamar a API `loadPaymentData`

Confira as próximas seções para descrições mais detalhadas de cada etapa.

## 1. Carregar o script principal

Primeiro, é necessário carregar a biblioteca JavaScript da API Google Pay em seu site, para isso utilize a tag de `<script>` igual ao exemplo a seguir:

### html

```html
<script async
  src="https://pay.google.com/gp/p/js/pay.js"
  onload="onGooglePayLoaded">
</script>
```

Depois que o script for carregado, inicialize um objeto `PaymentsClient` informando o ambiente a ser utilizado no campo `environment` , como no bloco de código abaixo:

### javascript

```javascript
function onGooglePayLoaded() {
	const paymentsClient =
    new google.payments.api.PaymentsClient({
      environment: 'TEST'
    });
};
```

O desenvolvimento inicial usa um ambiente `TEST`, que retorna formas de pagamento fictícias adequadas para referenciar a estrutura de uma resposta de pagamento. Nesse ambiente, uma forma de pagamento selecionada não é capaz de fazer uma transação.

> Ambiente de produção
> É importante pontuar que sem o cadastro junto a Google apenas é possível utilizar o ambiente de teste, para subir a solução para produção é necessário se registrar no [cadastro do Google](https://pay.google.com/business/console/) e enviar a aplicação para validação.

## 2. Chamar a API IsReadytoPay

Agora que ja construiu o seu objeto `PaymentsClient` a primeira API que você deve chamar é a `IsReadytoPay`. Essa chamada é importante para validar a compatibilidade do serviço do Google com o ambiente onde o cliente está acessando e se seu método de pagamento é valido.

O exemplo a seguir mostra como realizar a chamada:

### javascript

```javascript
const isReadyToPayRequest = Object.assign({}, baseRequest);
isReadyToPayRequest.allowedPaymentMethods = [baseCardPaymentMethod];

paymentsClient.isReadyToPay(isReadyToPayRequest)
    .then(function(response) {
      if (response.result) {
        // adicione um botão de pagamento Google Pay
      }
    })
    .catch(function(err) {
      // mostre erro no console para debugging
      console.error(err);
    });
```

Somente deve ser exibido o botão em caso de retorno positivo nessa chamada.

## 3. Chamar a API createButton

Essa é a API referente ao botão de chamada do Google, é muito importante utilizá-la para ter os padrões corretos do Google e aproveitar as melhorias de usabilidade que constantemente são entregues para a solução.

O exemplo a seguir mostra como realizar a chamada:

### javascript

```javascript
const button =
    paymentsClient.createButton({onClick: () => console.log('TODO: click handler'),
    allowedPaymentMethods: []}); // certifique-se de providenciar um método de pagamento válido
document.getElementById('container').appendChild(button);
```

## 4. Chamar a API loadPaymentData

O objeto `paymentDataRequest` é responsável por realizar as configurações da credencial de pagamento, bem como dados de e-mail, endereço e telefone e os métodos de pagamento aceitos. Este objeto é utilizado na API `loadPaymentData` para gerar o token de pagamento a ser enviado ao PagBank.

Construa seu objeto `paymentDataRequest` conforme o exemplo abaixo:

### javascript

```javascript
const paymentDataRequest = Object.assign({}, baseRequest);

paymentDataRequest.transactionInfo = {
  totalPriceStatus: 'FINAL',
  totalPrice: '123.45',
  currencyCode: 'USD',
  countryCode: 'US'
};

paymentDataRequest.merchantInfo = {
  merchantName: 'Example Merchant'
  merchantId: '12345678901234567890'
};
```

Crie um objeto chamado `cardPaymentMethod` para definir as configurações do método de pagamento, como apresentado no exemplo abaixo:

> Recomendação
> É recomendado que você mantenha os dois AuthMethods preenchidos.

### JavaScript

```javascript
const cardPaymentMethod = {
  type: 'CARD',
  tokenizationSpecification: tokenizationSpec,
  parameters: {
    allowedCardNetworks: ['VISA', 'ELECTRON', 'MASTERCARD', 'MAESTRO', 'ELO', 'ELO_DEBIT'],
    allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
    billingAddressRequired: true,
    billingAddressParameters: {
      format: 'FULL',
      phoneNumberRequired: true
    }
  }
};
```

Crie um novo objeto chamado `tokenizationSpec` para configurar a integração com o gateway de pagamento, onde você precisa informar `type =PAYMENT_GATEWAY`, além dos parametros `gateway` e `gatewayMerchantId`, como apresentado no code block a seguir:

### JavaScript

```javascript
const tokenizationSpec = {
  type: 'PAYMENT_GATEWAY',
  parameters: {
    gateway: 'pagbank',
    gatewayMerchantId: 'ACCO_01234567-8901-2345-6789-0123456789'
  }
};
```

Por fim, chame a API `loadPaymentData` passando o objeto criado como apresentado no exemplo a seguir:

### JavaScript

```java
paymentsClient.loadPaymentData(paymentDataRequest).then(function(paymentData){
  paymentToken = paymentData.paymentMethodData.tokenizationData.token;
    // Este é o token para passar ao PagBank
}).catch(function(err){
  console.error(err);
});
```

Extraia o token de pagamento da resposta `paymentData`. Passe esse token ao PagBank conforme descrito na integração para [processamento de pagamentos com Google Pay](../11-criar-e-pagar-um-pedido-com-google-pay.md).

> Liability Shift
> Os links abaixo listam as regras para transferência de liability que devem ser seguidas pelos clientes que desejam utilizar esse recurso. Acesse os conteúdos do Google e siga os procedimentos indicados:
> - [Critérios para transferências de liability](https://developers.google.com/pay/api/web/guides/resources/shift-liability-to-issuer)
> - [Ativação de transferência de liability para bandeira Visa](https://developers.googleblog.com/pt-br/google-pay-enabling-liability-shift-for-eligible-visa-device-token-transactions-globally/)
