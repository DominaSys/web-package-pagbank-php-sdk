# Webhooks

O PagBank pode enviar notificacoes via webhook quando houver mudanca de status de transacao ou de checkout.

## Configuracao

- Envie `notification_urls` no payload do checkout para receber eventos.

## Eventos

### Transacional

- `PAID`
- `IN_ANALYSIS`
- `DECLINED`
- `CANCELED`
- `WAITING`

### Checkout

- `EXPIRED`

## Exemplo

O payload de notificacao pode representar checkout e pagamento, incluindo eventos de PIX e cartao.
