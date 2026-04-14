# Pedidos e pagamentos (Order)

A API de Pedidos do PagBank, também conhecida como API Order, cobre as operações relacionadas à cobrança após a definição do pedido.

## Serviços disponíveis

Os principais meios de pagamento disponíveis incluem:

- cartão de crédito;
- cartão de débito;
- boleto;
- PIX;
- Pagar com PagBank;
- divisão de pagamento.

## Fluxos de utilização da API

O fluxo mais comum envolve:

1. criar o pedido;
2. processar o pagamento;
3. capturar a cobrança, quando aplicável.

Dependendo do meio de pagamento, esse fluxo pode variar.

## Explore os casos de uso

Se você estiver em dúvida sobre o fluxo certo, use os casos de uso da API de Pedidos:

- [Casos de uso de pedidos](../pedidos/casos-de-uso/01-criar-pedido.md)

## Endpoints e webhooks

A API de Pedidos possui endpoints para criação, consulta, pagamento e apoio a webhooks.

Para a confirmação de notificações, consulte:

- [Webhook de pedidos e pagamentos](../webhooks/01-confirmar-autenticidade-da-notificacao.md)

## Objetos

Os objetos principais da API incluem a estrutura do pedido e a estrutura da cobrança.

Consulte:

- [Objeto pedido](../pedidos/01-objeto-pedido.md)
- [Objeto charge](../pedidos/02-objeto-charge.md)

## Erros e bloqueios de pagamento

Consulte também:

- [Códigos de erro de pedidos e pagamentos](../01-codigos-erro.md)
- [Motivos de compra negada](../cobrancas/01-motivos-de-compra-negada.md)
