<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Factory;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Zuruuh\BlaguesApi\BlaguesApi;
use Zuruuh\BlaguesApi\BlaguesApiInterface;

/**
 * @api
 */
final class BlaguesApiFactory implements BlaguesApiFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function create(
        string $authToken,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?UriFactoryInterface $uriFactory = null,
        ?SerializerInterface $serializer = null
    ): BlaguesApiInterface {
        $httpClient ??= Psr18ClientDiscovery::find();
        $requestFactory ??= Psr17FactoryDiscovery::findRequestFactory();
        $uriFactory ??= Psr17FactoryDiscovery::findUriFactory();
        $serializer ??= new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return new BlaguesApi(
            $authToken,
            $httpClient,
            $requestFactory,
            $uriFactory,
            $serializer
        );
    }
}
