<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Client;

use Dominasys\PagBank\Exceptions\ApiException;
use Dominasys\PagBank\Exceptions\InvalidConfigurationException;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Response;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

final readonly class PagBankClient
{
    public function __construct(
        private Configuration $configuration,
        private ?ClientInterface $client = null,
    ) {
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function requestApi(string $method, string $uri, array $options = []): Response
    {
        return $this->request(
            baseUri: $this->configuration->endpoints->apiBaseUri(),
            method: $method,
            uri: $uri,
            options: $options,
            requireBearerToken: true,
            requireConnectCredentials: false,
            extraHeaders: [],
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function requestApiWithConnectCredentials(string $method, string $uri, array $options = []): Response
    {
        return $this->request(
            baseUri: $this->configuration->endpoints->apiBaseUri(),
            method: $method,
            uri: $uri,
            options: $options,
            requireBearerToken: true,
            requireConnectCredentials: true,
            extraHeaders: [],
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function requestApiWithAccountCredentials(string $method, string $uri, array $options = []): Response
    {
        return $this->request(
            baseUri: $this->configuration->endpoints->apiBaseUri(),
            method: $method,
            uri: $uri,
            options: $options,
            requireBearerToken: true,
            requireConnectCredentials: false,
            extraHeaders: [
                'x-client-id' => $this->requireClientId(),
                'x-client-secret' => $this->requireClientSecret(),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function requestApiWithClientToken(string $method, string $uri, string $clientToken, array $options = []): Response
    {
        if ($clientToken === '') {
            throw InvalidConfigurationException::missingAccountClientToken();
        }

        return $this->request(
            baseUri: $this->configuration->endpoints->apiBaseUri(),
            method: $method,
            uri: $uri,
            options: $options,
            requireBearerToken: true,
            requireConnectCredentials: false,
            extraHeaders: [
                'x-client-token' => $clientToken,
            ],
        );
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws GuzzleException
     */
    private function request(
        string $baseUri,
        string $method,
        string $uri,
        array $options,
        bool $requireBearerToken,
        bool $requireConnectCredentials,
        array $extraHeaders,
    ): Response {
        $options['base_uri'] = $baseUri;
        $options['http_errors'] = false;
        $options['timeout'] = $this->configuration->transport->timeout;
        $options['connect_timeout'] = $this->configuration->transport->connectTimeout;
        $options['headers'] = $this->prepareHeaders($options['headers'] ?? [], $requireBearerToken, $requireConnectCredentials, $extraHeaders);

        $response = $this->client()->request($method, $uri, $options);
        $pagBankResponse = Response::fromPsrResponse($response);

        if ($pagBankResponse->statusCode() >= 400) {
            throw ApiException::fromResponse($pagBankResponse);
        }

        return $pagBankResponse;
    }

    /**
     * @param  array<string, string>  $headers
     * @return array<string, string>
     */
    private function prepareHeaders(array $headers, bool $requireBearerToken, bool $requireConnectCredentials, array $extraHeaders): array
    {
        if ($requireBearerToken) {
            if (! is_string($this->configuration->credentials->bearerToken) || $this->configuration->credentials->bearerToken === '') {
                throw InvalidConfigurationException::missingBearerToken();
            }

            $headers['Authorization'] = 'Bearer ' . $this->configuration->credentials->bearerToken;
        }

        if ($requireConnectCredentials) {
            $headers['X_CLIENT_ID'] = $this->requireClientId();
            $headers['X_CLIENT_SECRET'] = $this->requireClientSecret();
        }

        foreach ($extraHeaders as $header => $value) {
            $headers[$header] = $value;
        }

        return $headers;
    }

    private function client(): ClientInterface
    {
        if ($this->client instanceof ClientInterface) {
            return $this->client;
        }

        return new Client();
    }

    private function requireClientId(): string
    {
        if (! is_string($this->configuration->credentials->clientId) || $this->configuration->credentials->clientId === '') {
            throw InvalidConfigurationException::missingConnectCredentials();
        }

        return $this->configuration->credentials->clientId;
    }

    private function requireClientSecret(): string
    {
        if (! is_string($this->configuration->credentials->clientSecret) || $this->configuration->credentials->clientSecret === '') {
            throw InvalidConfigurationException::missingConnectCredentials();
        }

        return $this->configuration->credentials->clientSecret;
    }
}
