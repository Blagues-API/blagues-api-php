<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
final class HttpClient implements ClientInterface
{
    private const API_DOMAIN = 'https://www.blagues-api.fr/';

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
            ->withUri($request->getUri()->withHost(self::API_DOMAIN))
        ;

        return $this->httpClient->sendRequest($request);
    }
}
