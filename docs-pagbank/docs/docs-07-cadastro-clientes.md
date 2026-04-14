# Cadastro de clientes

A API de Cadastro permite que parceiros PagBank criem contas em nome de clientes.

Ela também pode ser usada para conectar um cliente a uma conta já existente.

## Próximos passos após a criação da conta

Depois de criar a conta, é importante entender como a integração segue para os próximos serviços.

Se o cliente já tiver uma conta PagBank, você deve usar a API Connect para vinculá-lo corretamente à aplicação.

## Recursos disponíveis e fluxo de uso

A API de Cadastro fornece dois endpoints principais:

- criar conta;
- consultar conta.

### Criar conta

Ao criar uma conta, você precisa informar o tipo da conta e os dados necessários do cliente.

### Consultar conta

Use este endpoint para recuperar informações de uma conta já criada.

### Erros

Consulte os erros da API quando a criação ou consulta falhar.

## Restrições para a criação de contas

Nem todo fluxo exige criação de nova conta.

Quando o cliente já tiver conta PagBank, o caminho correto é conectá-lo por meio do Connect.

## Termos de uso e política de privacidade

O processo de abertura de conta depende da aceitação dos termos de uso e da política de privacidade do PagBank.

## Teste a criação de uma conta

Use o ambiente Sandbox para validar a integração antes de avançar para produção.

## Relação com outras páginas

- [Connect](connect/docs/01-connect.md)
- [Crie sua conta PagBank](03-01-requisitos-uso.md)
