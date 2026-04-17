# Confirmar autenticidade da notificação

Este guia explica como validar se uma notificação recebida é autêntica, usando a assinatura enviada no header
`x-authenticity-token`.

## 1. Salve o payload e o token

A assinatura é composta pelo token da sua conta e o payload recebido. Dessa forma, é importante salvar o token de sua
conta fornecido via iBanking e também armazenar o payload no recebimento da notificação.

Ao receber a notificação, salve exatamente o payload bruto e o token da sua conta. A assinatura é gerada a partir de
`token + "-" + payload`.

Ponto importante:

- o payload não deve ser reformatado
- qualquer espaço extra altera o hash

Um exemplo de payload recebido é apresentado a seguir:

```json
{"id":"CHAR_354828dd-786b-4cca-8ce4-6f7a1f3f2a1a","status":"WAITING","created_at":"2019-09-24T18:31:19.027-03:00","description":"This is the payment transaction description","amount":{"value":100,"currency":"BRL","summary":{"total":100,"paid":0,"refunded":0}},"payment_response":{"code":"20000","message":"SUCESSO"},"payment_method":{"type":"BOLETO","boleto":{"id":"eafadc9b-6e15-41ff-ab07-86218770d93b","barcode":"03399853012970000035869420601010782490000000100","formatted_barcode":"03399.85301 29700.000358 69420.601010 7 82490000000100","due_date":"2020-05-08","instruction_lines":{"line_1":"Pagamento processado para DESC Fatura","line_2":"Via PagSeguro"},"holder":{"name":"Jose da Silva","tax_id":"90792099028","email":"jose@email.com","address":{"region":"Sao Paulo","city":"Sao Paulo","postal_code":"01452002","street":"Avenida Brigadeiro Faria Lima","number":"1384","locality":"Pinheiros","country":"Brasil","region_code":"SP"}}}},"links":[{"rel":"SELF","href":"https://charge-rest.digital-payments.aws.intranet.pagseguro.uol/charges/354828dd-786b-4cca-8ce4-6f7a1f3f2a1a","media":"application/json","type":"GET"},{"rel":"SELF","href":"https://boleto.pagseguro.com.br/eafadc9b-6e15-41ff-ab07-86218770d93b.pdf","media":"application/pdf","type":"GET"},{"rel":"SELF","href":"https://boleto.pagseguro.com.br/eafadc9b-6e15-41ff-ab07-86218770d93b.png","media":"image/png","type":"GET"}],"notification_urls":["https://yourserver.com/nas_ecommerce/277be731-3b7c-4dac-8c4e-4c3f4a1fdc46/"],"metadata":{"Exemplo":"Aceita qualquer informação","NotaFiscal":"123","idComprador":"123456"}}
```

ATENÇÃO
O payload recebido não deve estar formatado, pois qualquer espaço adicional fará com que o hash tenha divergência e não
possa ser validado.

## 2. Prepare os os campos

Agora use o algoritmo SHA256 considerando o payload e o token salvos com um hífen entre eles ({token}-{payload}). O
bloco de código a seguir apresenta um exemplo para diferentes linguagens de programação:

Exemplo Go Lang:

```go
signature := sha256.Sum256([]byte(jsonToken.Token + - + request.Body))
```

Formato esperado:

```text
{token}-{payload}
```

## 3. Compare as assinaturas

Você irá receber o header no formato apresentado abaixo:

```header
x-authenticity-token : 8d09afba2eed749f2173cfd6faf16dc7f0228846ecf5ba1d7a11f33270d1513f
content-type : application/json
```

Compare a assinatura gerada para validação com o campo `x-authenticity-token` recebido no header.

ATENÇÃO
Se os tokens não coincidirem, o evento recebido deve ser descartado pois não será confiável.
