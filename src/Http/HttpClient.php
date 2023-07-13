<?php

declare(strict_types=1);

namespace BlaguesApi\Http;

use BlaguesApi\Exception\InvalidTokenException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 * @immutable
 */
final class HttpClient implements ClientInterface
{
    private const API_DOMAIN = 'www.blagues-api.fr';

    /**
     * @param non-empty-string $authToken
     */
    public function __construct(
        private ClientInterface $httpClient,
        private string $authToken
    ) {}

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $request
            ->withHeader('Authorization', "Bearer {$this->authToken}")
            ->withUri($request->getUri()->withHost(self::API_DOMAIN)->withScheme('https'))
        ;

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() === 401) {
            throw new InvalidTokenException();
        }

        return $response;
    }
}
