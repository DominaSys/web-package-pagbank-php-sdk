# Chaves públicas e de idempotência

Para garantir a segurança das operações realizadas através das APIs, o PagBank utiliza chaves públicas e chaves de idempotência.

## Chaves públicas

As chaves públicas são usadas para acessar o checkout transparente do PagBank.

Elas são necessárias quando você utiliza recursos como:

- criptografia de cartões;
- autenticação 3DS.

Ao criar uma chave pública, o PagBank armazena a chave privada correspondente.

Consulte a documentação de uso:

- [Chaves públicas](04-chaves-publicas.md)

## Chaves de idempotência

As chaves de idempotência são identificadores únicos usados para evitar a duplicação acidental de requisições.

Em uma API RESTful, enviar a mesma requisição com a mesma chave de idempotência deve produzir a mesma resposta, sem executar a operação duas vezes.

Isso é especialmente útil em cenários como:

- criação de pagamento;
- reenvio após falha de comunicação;
- prevenção de duplicidade em operações sensíveis.

## Relação com o restante da integração

Consulte também:

- [Token de autenticação](03-04-requisitos-uso-token-autenticacao.md)
- [Checkout](checkout/gerencie-checkout/01-criar-checkout.md)
