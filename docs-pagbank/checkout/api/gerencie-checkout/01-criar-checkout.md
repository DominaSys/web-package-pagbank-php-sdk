# Criar Checkout

`POST /checkouts`

Cria um checkout personalizado para cada pedido recebido.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Body Params

- `reference_id` `string`: Identificador único atribuído para o pedido. Utilizado internamente pelo vendedor em seu sistema. Máximo de 64 caracteres.
- `expiration_date` `date-time`: Data de expiração do checkout em ISO-8601. Caso não seja informada, a expiração será a data e hora da criação do checkout + 2 horas.
- `customer` `object`: Objeto contendo os dados pessoais do comprador. Obrigatório caso `customer_modifiable` seja `false`. Nesse caso, todos os campos do objeto são obrigatórios.
  - `name` `string`: Nome do cliente, devendo conter nome e sobrenome. Caracteres especiais são permitidos, mas serão removidos. Apóstrofo e números são aceitos e não serão removidos.
  - `email` `string`: E-mail do cliente.
  - `tax_id` `string`: Documento de identificação pessoal do cliente. Aceita CPF de 11 caracteres ou CNPJ de 14 caracteres.
  - `phone` `object`: Objeto com os dados do telefone do cliente.
    - `country` `string`: Código do país (DDI) do telefone do cliente. Somente o caractere especial `+` é aceito. Somente o código do Brasil (`55`) é aceito.
    - `area` `string`: Código de área (DDD) do telefone do cliente.
    - `number` `string`: Número do telefone do cliente contendo 9 caracteres. Deve sempre iniciar com o número 9.
- `customer_modifiable` `boolean`: Indicador da imutabilidade dos dados pessoais na criação do checkout. Caso não informado, o valor padrão é `true`. O objeto `customer` torna-se obrigatório caso o valor informado seja `false`.
- `items` `array of objects`: Lista de produtos associados ao pedido. Obrigatório.
  - `reference_id` `string`: Referência do produto informado pelo vendedor. Máximo de 100 caracteres.
  - `name` `string`: Nome do produto informado pelo vendedor. Máximo de 100 caracteres.
  - `description` `string`: Descrição do produto informado pelo vendedor. Máximo de 255 caracteres.
  - `quantity` `int32`: Quantidade do produto associado ao pedido. Obrigatório.
  - `unit_amount` `int32`: Valor unitário do produto em centavos. Máximo de 999999900. Obrigatório.
  - `image_url` `string`: URL da imagem do produto exibida na lista de itens do checkout. Aceita `.png`, `.jpg` e `.jpeg`. Formato JPG/PNG, tamanho máximo de 15 MB.
- `additional_amount` `int32`: Valor adicional a ser cobrado em centavos. Máximo de 999999900. É um valor complementar ao total resultante da soma dos itens do pedido.
- `discount_amount` `int32`: Valor a ser descontado do valor total da compra em centavos. Máximo de 999999900. Não deve superar a soma do valor dos itens com o valor adicional (`additional_amount`).
- `shipping` `object`: Dados de entrega do produto. Caso não informado, entende-se que não há necessidade de entrega. Se informado, é necessário definir se o valor da entrega é fixo, grátis ou calculado.
  - `type` `string`: Tipo de entrega associada ao pedido. Valores aceitos: `FIXED`, `FREE`, `CALCULATE`.
  - `service_type` `string`: Tipo de serviço de entrega utilizado para calcular o frete. Se não for informado, o cliente poderá escolher entre as opções na tela do checkout. Valores aceitos: `SEDEX`, `PAC`.
  - `address_modifiable` `boolean`: Indica se o endereço pode ser alterado na tela de endereço de entrega do checkout. Caso não informado, o valor padrão é `true`. Se `false`, o objeto `shipping.address` torna-se obrigatório.
  - `amount` `int32`: Valor do custo da entrega em centavos. Máximo de 2147483647. Obrigatório caso `shipping.type` seja `FIXED`.
  - `address` `object`: Endereço de entrega. Obrigatório caso `address_modifiable` seja `false`.
    - `street` `string`: Logradouro do endereço de entrega. Máximo de 160 caracteres. Obrigatório.
    - `number` `string`: Número do endereço de entrega. Máximo de 20 caracteres. Obrigatório.
    - `complement` `string`: Complemento do endereço de entrega.
    - `locality` `string`: Bairro do endereço de entrega. Máximo de 60 caracteres. Obrigatório.
    - `city` `string`: Cidade do endereço de entrega. Máximo de 90 caracteres. Obrigatório.
    - `region_code` `string`: Estado do endereço de entrega. Formato ISO 3166-1 alfa-3. Obrigatório.
    - `country` `string`: País do endereço de entrega. Formato ISO 3166-1 alfa-3. Obrigatório.
    - `postal_code` `string`: CEP do endereço de entrega. 8 caracteres. Obrigatório.
  - `box` `object`: Define o tamanho e peso da caixa de entrega. Obrigatório caso `shipping.type` seja `CALCULATE`.
    - `weight` `string`: Peso da caixa de entrega. Obrigatório.
    - `dimensions` `object`: Dimensões da caixa. Obrigatório.
      - `length` `int32`: Comprimento da caixa em centímetros. Intervalo de 15 a 100. Obrigatório.
      - `width` `int32`: Largura da caixa em centímetros. Intervalo de 10 a 100. Obrigatório.
      - `height` `int32`: Altura da caixa em centímetros. Intervalo de 1 a 100. Obrigatório.
- `payment_methods` `array of objects`: Define quais meios de pagamento você deseja que sejam aceitos no checkout.
  - `type` `string`: Meio de pagamento escolhido pelo vendedor. Valores aceitos: `CREDIT_CARD`, `DEBIT_CARD`, `BOLETO`, `PIX`.
- `payment_methods_configs` `array of objects`: Configuração dos meios de pagamento. Aplicável apenas para `CREDIT_CARD` e `DEBIT_CARD`.
  - `type` `string`: Meio de pagamento a ser configurado. Valores aceitos: `CREDIT_CARD`, `DEBIT_CARD`.
  - `config_options` `array of objects`: Lista de opções de configuração.
    - `option` `string`: Opção de configuração do meio de pagamento. Valores aceitos: `INSTALLMENTS_LIMIT`, `INTEREST_FREE_INSTALLMENTS`.
    - `value` `string`: Valor da configuração dada ao meio de pagamento.
- `soft_descriptor` `string`: Texto adicional apresentado junto ao nome do estabelecimento na fatura do cartão de crédito do comprador. Máximo de 17 caracteres.
- `redirect_url` `string`: URL para redirecionamento do comprador após a finalização do pagamento. Máximo de 255 caracteres.
- `notification_urls` `array of strings`: Lista de URLs para as quais o PagBank enviará notificações sobre atualizações do status do checkout. Máximo de 100 caracteres por URL.
- `payment_notification_urls` `array of strings`: Lista de URLs para as quais o PagBank enviará notificações sobre a atualização do status do pagamento associado ao checkout. Mínimo de 5 e máximo de 100 caracteres por URL.
- `recurrence_plan` `object`: Objeto com as informações das configurações do plano de cobrança recorrente.
  - `name` `string`: Nome do plano na sua aplicação. Máximo de 100 caracteres. Obrigatório.
  - `billing_cycles` `int32`: Número de ciclos (faturas) da assinatura antes da expiração. Deixe em branco para que a assinatura não expire automaticamente.
  - `interval` `object`: Objeto contendo os detalhes de intervalo de tempo das cobranças.
    - `unit` `string`: Define a frequência com que a cobrança será realizada. Valores aceitos: `MONTH` (mensal) e `YEAR` (anual). Valor padrão: `MONTH`.
    - `length` `int32`: Quantidade de unidades (`unit`) entre cada cobrança da assinatura. Exemplo: `3` com `unit = MONTH` resulta em uma cobrança a cada 3 meses.
- `return_url` `string`: URL para redirecionamento do comprador após concluir ou cancelar o pagamento no Checkout PagBank. Deve apontar para sua loja para permitir o retorno após a finalização da transação. Máximo de 255 caracteres.

## Responses

- `201`
- `400`
