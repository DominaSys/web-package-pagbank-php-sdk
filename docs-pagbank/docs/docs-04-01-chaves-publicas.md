# Chaves públicas

Na criptografia, uma chave pública é usada para criptografar dados ou verificar assinaturas digitais.

Ela trabalha em conjunto com uma chave privada, que permanece sob controle do proprietário e é usada para descriptografar o conteúdo.

## Como as chaves públicas são usadas pelo PagBank

No PagBank, chaves públicas são usadas principalmente para:

- acessar o checkout transparente;
- criptografar cartões;
- autenticar transações com 3DS.

Quando você cria uma chave pública com a API PagBank, o PagBank armazena a chave privada correspondente.

Isso permite que dados criptografados com a chave pública sejam lidos apenas pelo PagBank.

## Utilizando chaves públicas

O PagBank oferece três operações principais para gerenciar chaves públicas:

- criar chave pública;
- consultar chave pública;
- alterar chave pública.

## Criar chave pública

Use essa operação para gerar uma chave pública vinculada à sua conta.

No ambiente Sandbox, a resposta costuma retornar uma chave padrão fixa.

No ambiente de Produção, a chave pública retornada é única e vinculada à sua conta PagBank.

### Validade

As chaves públicas geradas pelo PagBank não expiram automaticamente.

Mesmo assim, a recomendação é renovar periodicamente, com intervalo inferior a 2 anos.

## Consultar chave pública

Use essa operação para consultar os dados da chave pública vinculada à sua conta PagBank.

## Alterar chave pública

Use essa operação para renovar sua chave pública.

Você só pode alterar uma chave pública após 7 dias da criação.

### Validade da chave pública antiga

Quando uma nova chave pública é gerada, a chave anterior continua válida por 7 dias.

Depois desse prazo, a chave antiga deixa de funcionar.

## Relação com o checkout transparente

Se você vai usar criptografia de cartões ou 3DS, esta página é obrigatória na sua integração.

Veja também:

- [Chaves públicas e de idempotência](03-05-requisitos-uso-chaves-publicas-e-de-idempotencia.md)
