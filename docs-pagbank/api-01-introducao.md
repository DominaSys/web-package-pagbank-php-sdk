# Introdução

Bem-vindo ao guia do desenvolvedor das APIs do PagBank. Neste guia, você encontrará todas as informações necessárias para integrar e utilizar as funcionalidades oferecidas pelas nossas APIs. Estas foram implementadas seguindo o padrão RESTful. Utilizamos protocolos HTTP padrão, onde as respostas às solicitações HTTP são retornadas em formato JSON. Todas as operações podem ser realizadas por meio de requisições GET, POST e PUT.

## Serviços

As APIs do PagBank oferecem uma variedade de serviços para facilitar pagamentos e transferências digitais.

### Pagamento

Os cards abaixo listam as principais funcionalidades englobadas pelas APIs associadas à realização de pagamentos.

- **[API de Cadastro](contas/gerencie-contas/01-criar-conta.md)**: possibilita que parceiros PagBank criem contas em nome de terceiros. Com essa ferramenta você garante que conta de terceiros sejam criadas de forma eficiente e estejam prontas para transações rapidamente.
- **Connect**: o serviço Connect permite que você conecte a sua aplicação com contas de outros usuários PagBank para realizar ações em nome deles. Essa funcionalidade amplia as possibilidades de integração e interação com a plataforma, sendo normalmente utilizado por plataformas ou marketplaces.
- **[API de Pedido](pedidos-pagamentos/pedidos/gerencie-pedidos/01-criar-pedido.md)**: a API de Pedidos simplifica a gestão de compras e pagamentos, oferecendo opções variadas de formas de pagamento como Cartão de Crédito, Débito, Boleto ou PIX, além de fornecer funcionalidades complementares, como divisão do pagamento.
- **API de Pagamentos Recorrentes**: o serviço de Pagamentos Recorrentes do PagBank possibilita a cobrança automática personalizada para serviços de assinatura. Por meio da criação de planos, é possível definir valores e intervalos de cobrança, facilitando o gerenciamento de mensalidades, assinaturas, contas de assinantes e cobranças recorrentes.
- **API de Transferência**: movimente saldo entre contas, proporcionando flexibilidade e agilidade na gestão financeira dos seus clientes. Essa funcionalidade facilita a transferência de fundos, contribuindo para uma administração financeira mais dinâmica.
- **[API de Checkout PagBank](checkout/gerencie-checkout/01-criar-checkout.md)**: o Checkout PagBank simplifica a oferta de métodos de pagamento, eliminando a necessidade de integrações complexas. Você irá ao direcionar os clientes para uma página exclusiva do PagBank para efetuar o checkout. Após a transação, os seus são redirecionados para a página especificada.

### Complementares

Além dos serviços principais vinculados a realização de pagamentos, o PagBank dispõe de serviços complementares que são acessados por APIs.

- **Chaves públicas**: as chaves públicas são utilizadas para acessar o checkout transparente do PagBank. Se você deseja utilizar recursos como criptografia de cartões e autenticação 3DS, será necessário utilizar as chaves públicas.
- **Certificação digital**: a certificação digital possibilita a criação de um certificado digital mTLS, utilizados como fator adicional de segurança no processo de integração das APIs do PagBank. Garanta a proteção dos dados e transações com essa funcionalidade.
- **EDI**: o sistema de Intercâmbio Eletrônico de Dados (EDI) possibilita que transmita de forma eficiente extratos eletrônicos. Essa solução permite que os você reconciliem suas transações de vendas e pagamentos de maneira ágil e simplificada.
