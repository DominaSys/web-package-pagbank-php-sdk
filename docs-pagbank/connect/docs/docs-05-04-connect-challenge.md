# Connect challenge

Connect challenge adiciona uma etapa de validação baseada em criptografia assimétrica.

Esse fluxo é usado quando a integração precisa de uma camada extra de segurança, com chave pública, chave privada e descriptografia do desafio.

## Quando usar

Use Connect challenge quando a sua integração precisar:

- operar com certificação digital;
- validar posse de uma chave privada;
- obter um `challenge` criptografado para posterior descriptografia local.

## Visão geral do fluxo

1. Gere o par de chaves.
2. Disponibilize a chave pública em um endpoint.
3. Informe a URL da chave pública ao PagBank.
4. Gere o `challenge` e troque por `access_token`.

## 1. Gerar as chaves pública e privada

Gere um par RSA com, no mínimo, 2048 bits.

A chave pública deve seguir o formato SPKI/x.509 e a privada deve seguir PKCS8.

## 2. Disponibilize sua chave pública

A chave pública deve ficar disponível em um endpoint `GET` com resposta JSON.

O payload esperado contém:

- `public_key`
- `created_at`

## 3. Informe a URL da chave pública

Depois de expor a chave pública, informe a URL ao PagBank.

No Sandbox, isso costuma ser feito por abertura de solicitação; em produção, via área de integração da conta.

## 4. Gere o challenge e o token de acesso

Com a URL cadastrada, a troca de credenciais pode solicitar um `challenge`.

O retorno vem criptografado com a chave pública e deve ser:

1. decodificado de base64;
2. descriptografado com a chave privada;
3. tratado com OAEP, MGF1 e SHA-256.

## Relação com o access token

O `challenge` é uma etapa intermediária.

Depois de validá-lo, a aplicação continua o fluxo normal de obtenção de token.

Consulte:

- [Obter access token](../api/gerencia-connect/05-obter-access-token.md)

## Próximas referências

- [Connect](01-connect.md)
- [Connect Authorization](02-connect-authorization.md)
- [Connect via SMS](03-connect-sms.md)
