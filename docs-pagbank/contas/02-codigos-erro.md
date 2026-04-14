# Códigos de erro

Ao fazer requisições para a API Connect, parâmetros incorretos podem gerar erros que impedem o fornecimento de uma resposta com os dados esperados. Nesse caso, erros serão retornados como resposta à sua requisição para ajudá-lo a entender o problema.

Os códigos de erro apresentados abaixo podem ocorrer durante a interação com a API Connect. Eles são acompanhados por descrições curtas e pelo cenário correspondente.

| Código | Descrição | Cenário |
| --- | --- | --- |
| `40001` | Parâmetro obrigatório | Algum dado obrigatório não foi informado. |
| `40002` | Parâmetro inválido | Algum dado foi informado com formato inválido ou o conjunto de dados não cumpriu todos os requisitos de negócio. |
| `42001` | Falha na criação de conta | A conta já existe no PagBank. Para ter acesso aos dados dessa conta ou criar pagamentos em nome do dono da conta, é necessário solicitar permissão via API Connect. |
| `42002` | Falha na criação de conta | O processo de criação foi iniciado por outro canal diferente da API. O usuário precisa acessar o e-mail para finalizar a criação de conta. |
