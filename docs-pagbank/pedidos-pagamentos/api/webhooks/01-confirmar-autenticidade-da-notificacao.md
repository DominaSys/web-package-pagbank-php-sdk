# Confirmar autenticidade da notificação

Este guia explica como validar se uma notificação recebida é autêntica, usando a assinatura enviada no header `x-authenticity-token`.

## 1. Salve o payload e o token

Ao receber a notificação, salve exatamente o payload bruto e o token da sua conta. A assinatura é gerada a partir de `token + "-" + payload`.

Ponto importante:
- o payload não deve ser reformatado
- qualquer espaço extra altera o hash

## 2. Gere a assinatura

Use o algoritmo `SHA-256` sobre a string montada com o token, um hífen e o payload bruto.

Formato esperado:

```text
{token}-{payload}
```

## 3. Compare as assinaturas

Compare a assinatura gerada com o valor recebido no header `x-authenticity-token`.

Se os valores não coincidirem, descarte o evento, porque a notificação não é confiável.
