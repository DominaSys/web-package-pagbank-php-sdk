# Criptografe o cartão

O primeiro passo para que você crie e pague um pedido usando Cartão de Crédito é criptografar os dados do cartão. Para isso o PagBank disponibiliza um SDK. Dessa forma, sua página tem acesso as funções de criptografia dos dados disponibilizada pelo PagBank. Assim, a criptografia dos dados sensíveis do Cartão de Crédito é feita diretamente no navegador, reduzindo o seu escopo PCI.

Outro benefício de utilizar o SDK do PagBank, é que ele não requer chamadas ao servidor. Ou seja, o processo de criptografia é feito localmente. A criptografia utiliza a sua chave pública e é realizada usando o algoritmo RSA. A função que realiza a criptografia fornece uma string que pode ser descriptografada usando a chave privada, a qual apenas o PagBank tem acesso.

Para utilizar o SDK do PagBank você deve incluir o script apresentado a seguir antes de fechar a tag `<body>` da sua página:

```html
<script src="https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js"></script>
```

Após incluir o SDK você pode utilizar todas as funções disponibilizadas pelo PagBank. Para realizar a criptografia dos dados do Cartão de Crédito você irá utilizar o método `PagSeguro.encryptCard()` fornecendo os seguintes dados:

| Parâmetro | Descrição |
| --- | --- |
| `publicKey` | Sua chave pública. Acesse [Introdução às chaves públicas](/docs/chaves-publicas) para maiores detalhes. |
| `holder` | Nome completo do portador do cartão. |
| `number` | Número do Cartão de Crédito. |
| `expMonth` | Mês de expiração do Cartão de Crédito. |
| `expYear` | Ano de expiração do Cartão de Crédito. |
| `securityCode` | Código de segurança do Cartão de Crédito. |

> [!NOTE]
> **Utilize os cartões de teste encriptados**
>
> Na página de [cartões de teste](/docs/cartoes-de-teste) você encontra uma lista de cartões fictícios que quando utilizados resultam em transações com resultados pré-estabelecidos. Assim, você pode testar a aplicação já sabendo seu resultado.
>
> Além disso, você consegue executar a criptografia dos cartões diretamente nessa página. Selecione o cartão de teste desejado e utilize o botão **Criptografe este cartão** para ter os dados do cartão criptografados disponibilizados na sua área de transferência. Assim, você pode pular a etapa de criptografia em seus testes iniciais.

A seguir é apresentado um exemplo de criptografia dos dados do cartão utilizando o método `encryptCard`:

```javascript
const card = PagSeguro.encryptCard({
  publicKey: "MINHA_CHAVE_PUBLICA",
  holder: "Nome Sobrenome",
  number: "4242424242424242",
  expMonth: "12",
  expYear: "2030",
  securityCode: "123"
});

const encrypted = card.encryptedCard;
const hasErrors = card.hasErrors;
const errors = card.errors;
```

Conforme apresentado no exemplo acima, o método `encryptCard` fornece um objeto. Os dados desse objeto são listados e descritos na tabela a seguir:

| Parâmetro | Descrição |
| --- | --- |
| `encryptedCard` | Cartão criptografado, que deve ser adicionado à requisição ao endpoint Criar pedido. |
| `hasErrors` | Determina se houve, ou não, algum erro durante o processo de criptografia. |
| `errors` | Se algum erro ocorreu durante a criptografia, esse parâmetro fornece uma lista dos erros, incluindo seu código e mensagem de erro. |

Os possíveis erros decorrentes do processo de criptografia dos dados do cartão contidos no parâmetro `errors` são apresentados na sequência:

| Código (code) | Mensagem (message) |
| --- | --- |
| `INVALID_NUMBER` | Invalid card number |
| `INVALID_SECURITY_CODE` | Invalid field `securityCode`. You must pass a value with 3, 4 or none digits |
| `INVALID_EXPIRATION_MONTH` | Invalid field `expMonth`. You must pass a value between 1 and 12 |
| `INVALID_EXPIRATION_YEAR` | Invalid field `expYear`. You must pass a value with 4 digits |
| `INVALID_PUBLIC_KEY` | Invalid `publicKey` |
| `INVALID_HOLDER` | Invalid holder |
