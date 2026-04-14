# Ambientes disponíveis

As APIs do PagBank operam em dois ambientes principais:

- **Sandbox**
- **Produção**

## Sandbox

O Sandbox é o ambiente de testes.

Use esse ambiente para:

- validar integrações;
- testar cenários sem impacto real;
- simular respostas e fluxos.

## Produção

O ambiente de Produção deve ser usado apenas após a homologação.

Ele representa a operação real da sua aplicação com contas e credenciais válidas.

## URL de cada ambiente

Cada serviço possui URLs diferentes para Sandbox e Produção.

Ao integrar, verifique sempre:

- a URL correta do ambiente;
- se a credencial informada pertence ao mesmo ambiente;
- se o fluxo já foi homologado para produção.

## Relação com outros requisitos

Consulte também:

- [Crie sua conta PagBank](03-01-requisitos-uso.md)
- [Token de autenticação](03-04-requisitos-uso-token-autenticacao.md)
