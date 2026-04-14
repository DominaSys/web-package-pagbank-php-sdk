# Certificado digital

O certificado digital mTLS é usado para garantir comunicação segura entre o seu sistema e o PagBank.

Ele combina autenticação e criptografia na comunicação entre cliente e servidor.

## O que são os certificados digitais

Os certificados digitais utilizados pelo PagBank são do tipo `mTLS`:

- `m` de mutual;
- `TLS` de Transport Layer Security.

## Proteja o seu certificado digital

O certificado digital é confidencial e deve ser armazenado com cuidado.

Boas práticas:

- não compartilhe o certificado em ambientes públicos;
- restrinja o acesso ao material sensível;
- armazene os arquivos em local seguro.

## Crie um certificado digital

Para criar um certificado digital, você precisa concluir passos anteriores relacionados à integração e ao vínculo com a conta.

Depois disso, use o endpoint de criação de certificado digital da API correspondente.

## Validade e atualização do certificado digital

- certificados digitais normalmente têm validade de 2 anos;
- após a expiração, é necessário gerar um novo certificado;
- se você gerar um novo certificado antes do vencimento, o certificado anterior será invalidado imediatamente.

## Relação com outras APIs

O certificado digital é mais relevante para integrações que exigem maior camada de segurança, especialmente em cenários ligados a transferência e Pix Bacen.
