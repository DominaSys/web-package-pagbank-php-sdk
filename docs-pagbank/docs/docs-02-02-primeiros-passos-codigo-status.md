# Códigos de status

A PagBank usa códigos HTTP convencionais para indicar sucesso ou falha em requisições.

Em geral:

- `2xx` indica sucesso;
- `4xx` indica erro causado pelos dados informados;
- `5xx` indica erro do servidor.

## Códigos utilizados

| Código | Descrição | Cenário |
| --- | --- | --- |
| `400` | Bad Request | Parâmetro mal formatado, valor inesperado ou conteúdo inválido na requisição. O corpo da resposta normalmente ajuda a identificar o problema. |
| `401` | Unauthorized | Falha na identificação do cliente, geralmente por ausência ou valor inválido no header `Authorization`. |
| `403` | Forbidden | O cliente ainda não tem permissão para acessar a API ou o recurso solicitado. Em produção, o acesso pode depender de homologação. |
| `404` | Not Found | O identificador informado não existe ou houve problema de sincronização/consulta da transação. |
| `406` | Not Acceptable | O método HTTP utilizado está incorreto para o endpoint. |
| `409` | Conflict | A chave de idempotência já está em uso dentro do período permitido. |
| `500` | Internal Server Error | O serviço não conseguiu identificar o erro específico. |

## Quando usar esta página

Use esta referência quando quiser interpretar o status HTTP retornado por uma requisição.

Para erros mais específicos por produto, consulte também:

- [Códigos de erro do Connect](connect/api/01-codigos-erro.md)
- [Códigos de erro do Checkout](checkout/02-codigos-erro.md)
- [Códigos de erro de Pedidos e Pagamentos](pedidos-pagamentos/01-codigos-erro.md)
