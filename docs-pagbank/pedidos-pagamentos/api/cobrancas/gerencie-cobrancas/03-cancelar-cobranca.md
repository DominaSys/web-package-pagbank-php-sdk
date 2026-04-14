# Cancelar cobrança

`POST /charges/{charge_id}/cancel`

Devolve o valor cobrado ao comprador, seja para desfazer uma pre-autorizacao ou para reembolsar uma cobrança capturada.

## Path Params

- `charge_id` `string`: obrigatorio. Identificador da cobrança PagBank.

## Headers

- `Authorization` `string`: obrigatorio, no formato `Bearer <token>`.

## Body Params

- `amount` `object`: Contém as informações do valor da cobrança.
  - `value` `int32`: Valor a ser cobrado em centavos. Apenas números inteiros positivos. Exemplo: R$ 1.500,99 = 150099.
- `splits` `object`: Utilizado para cancelamento customizado de pedidos com divisão de pagamento. Informa o método e os recebedores envolvidos.
  - `method` `string`: Especifica como a divisão do cancelamento será realizada.
    - `FIXED`: deve ser informado os valores monetários a ser debitado da conta de cada recebedor.
    - `PERCENTAGE`: deve ser informado o percentual do montante do cancelamento a ser debitado da conta de cada recebedor.
  - `receivers` `array of objects`: Especifica as contas que terão valores debitados após o cancelamento, informando o valor ou percentual a ser debitado.
    - `account` `object`: Informa os dados do recebedor que terá fundos debitados da conta após o cancelamento.
      - `id` `string`: Identificador da conta do recebedor participante da operação de cancelamento.
    - `amount` `object`: Informa o valor ou percentual da operação de cancelamento a ser debitado da conta do recebedor.
      - `value` `int32`: Valor fixo ou percentual que deve ser debitado da conta do recebedor participante da operação de cancelamento.
    - `configurations` `object`: Informa se o recebedor será responsável por arcar com as táxas reembolsáveis ou com as diferenças decorrentes dos arredondamentos.
      - `refund` `object`: Objeto especificando se o recebedor será responsável por arcar diferenças decorrentes de arredondamentos.
        - `rounding_liable` `object`: Objeto especificando se o recebedor será responsável por arcar diferenças decorrentes de arredondamentos.
          - `apply` `boolean`: Informa se o recebedor será responsável por arcar com diferenças decorrentes do processo de arredondamentos para duas casas decimais.
      - `fee_liable` `object`: Objeto especificando se o recebedor irá arcar com as taxas reembolsáveil.
        - `percentage` `int32`: Especifica o valor percentual que será debitado da conta do recebedor para arcar com o valor das taxas reembolsáveis. Como somente um recebedor pode ser responsável, sempre deve ser informado o valor de 100 (100%).

## Responses

- `201`
- `400`
