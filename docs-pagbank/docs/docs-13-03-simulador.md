# Simulador

O simulador permite testar cenários do ambiente de produção dentro do Sandbox.

## Pagamentos com Boleto

Você pode simular situações em que:

- o boleto é pago corretamente;
- o boleto expira sem pagamento.

## Pagamentos com PIX

O simulador também cobre cenários de PIX, incluindo estados como `paid` e `waiting`.

## Importante

Nem todos os fluxos estão cobertos por simulação.

Por exemplo, pagamentos via API PIX podem não estar contemplados no simulador.

## Como o simulador identifica os cenários

A identificação do cenário normalmente depende do valor enviado na transação.

## Próximo passo

Depois de testar, valide se a integração está pronta para homologação.
