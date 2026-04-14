# Liberar divisao de cobranca com custodia

`POST /splits/{split_id}/custody/release`

Este endpoint permite fazer a liberacao da custodia de um pedido com divisao de cobranca.

A liberacao pode ser feita para um ou mais recebedores envolvidos. Depois desta etapa, os valores ficam disponiveis para transferencias das contas dos vendedores.

> Apenas o usuario dono da transacao pode realizar a liberacao da custodia.

> Este endpoint usa uma URL base diferente dos demais endpoints da API Connect: `https://sandbox.api.pagseguro.com/splits/`

> A liberacao de custodia esta sujeita a um limite de ate 500 requisicoes a cada 5 minutos.

> Solicitacoes bem sucedidas recebem uma resposta vazia com codigo 200.

## Path Params

### `split_id` `string` `required`

Identificador unico da divisao da cobranca.

Voce recebe essa informacao quando faz a cobranca de um pedido ou quando cria e paga o pedido na mesma operacao.

## Headers

- `Authorization` `string`: obrigatorio, token do recebedor primario.

## Body Params

### `receivers` `array of objects`

Contem as informacoes necessarias para o desbloqueio da custodia de um recebedor especifico na divisao da cobranca.

#### `receivers[].account` `object`

Informacao para identificacao do recebedor da divisao da cobranca.

- `receivers[].account.id` `string`: identificador unico do recebedor da divisao da cobranca.

## Responses

### `200`

Solicitacao realizada com sucesso.

### `400`

Requisicao invalida.
