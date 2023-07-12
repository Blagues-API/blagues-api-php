<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Factory;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Zuruuh\BlaguesApi\BlaguesApiInterface;

/**
 * @api
 */
interface BlaguesApiFactoryInterface
{
    /**
     * @param non-empty-string $authToken
     */
    public static function create(
        string $authToken,
        ?ClientInterface $httpClient,
        ?RequestFactoryInterface $requestFactory,
        ?UriFactoryInterface $uriFactory,
        ?SerializerInterface $serializer
    ): BlaguesApiInterface;
}
