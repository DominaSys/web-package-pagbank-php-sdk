# Códigos de Erro

Ao fazer requisições para a API Connect, parâmetros incorretos podem gerar erros que impedem o fornecimento de uma resposta com os dados esperados. Nesse caso, os erros são retornados como resposta à sua requisição para ajudar na identificação do problema.

Os códigos abaixo podem ocorrer durante a interação com a API Connect.

| Código | Descrição | Cenário |
| --- | --- | --- |
| 41001 | `invalid_request` | Parâmetro obrigatório não foi enviado ou algum parâmetro possui um valor inválido. |
| 41002 | `invalid_redirect_uri` | A URL fornecida é inválida. |
| 41003 | `invalid_client` | Falha na identificação do cliente. |
| 41004 | `invalid_grant` | O `code` ou `refresh_token` são inválidos, expiraram, já foram utilizados ou não pertencem ao usuário atual. |
| 41005 | `unsupported_grant_type` | Apenas `authorization_code` e `refresh_token` são tipos suportados. |
| 41006 | `unauthorized_client` | O cliente não está autorizado a executar essa ação. |
| 41007 | `unsupported_token_type` | Apenas `access_token` e `refresh_token` são tipos suportados no momento de revogação de token. |
| 41008 | `invalid_token` | Bearer token inválido fornecido em `Authorization`. |
| 41012 | `token_is_no_longer_active` | O `access_token` ou o `refresh_token` já foram utilizados ou não pertencem ao usuário atual. |
| 41013 | `not_found_url` | A URL não está parametrizada para o cliente informado. |
| 41014 | `not_found_public_key` | A URL parametrizada não teve o retorno esperado. |
| 41015 | `invalid_format_url` | A URL parametrizada está em formato inválido. |
| 41016 | `invalid_public_key` | A Public Key encontrada não é uma chave válida. |

