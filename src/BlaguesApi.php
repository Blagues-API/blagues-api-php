<?php

declare(strict_types=1);

namespace Blagues;

use Blagues\Exceptions\ApiUnavailableException;
use Blagues\Exceptions\InvalidJokeTypeException;
use Blagues\Exceptions\InvalidResponseShapeException;
use Blagues\Exceptions\InvalidTokenException;
use Blagues\Exceptions\JokeException;
use Blagues\Models\Joke;
use Http\Discovery\Psr17Factory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Zuruuh\BlaguesApi\Http\HttpClient;

class BlaguesApi implements BlaguesApiInterface
{
    private HttpClient $httpClient;
    private RequestFactoryInterface $requestFactory;
    private UriFactoryInterface $uriFactory;
    private SerializerInterface $serializer;

    /**
     * @param non-empty-string $authToken
     */ 
    public function __construct(
        string $authToken,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?UriFactoryInterface $uriFactory = null,
        ?SerializerInterface $serializer = null,
    ) {
        $httpClient ??= Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
        $this->httpClient = new HttpClient($httpClient, $authToken);
        $this->serializer = $serializer ?? new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @throws JokeException|GuzzleException
     *
     * @phpstan-return array<string, int|string>
    private function request(string $uri): array
    {
        try {
            $res = $this->httpClient->get($uri);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode === 401) {
                throw new InvalidTokenException($e);
            }
            if ($statusCode === 404) {
                return [];
            }
            if ($statusCode >= 500) {
                throw new ApiUnavailableException($e);
            }

            throw $e;
        }

        $json = (string) $res->getBody();
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new JokeException(
                'Invalid server response! Please report this in a new issue' .
                'on this package\'s git repository (https://github.com/Blagues-API/blagues-api-php/issues/new).'
            );
        }

        return $data;
    }
     */

    /**
     * {@inheritdoc}
     */
    public function getRandom(array $disallowed = []): Joke
    {
        $query = '';
        if (!empty($disallowed)) {
            if (count($disallowed) === count(JokeTypeInterface::TYPES)) {
                throw new InvalidJokeTypeException('You cannot disable all joke types !');
            }

            foreach ($disallowed as $type) {
                $this->validateType($type);
            }

            $query = implode('&disallow=', $disallowed);
        }

        $joke = $this->request('/api/random?disallow=' . $query);
        /* $this->httpClient->sendRequest($this->requestFactory->createRequest('GET', $this->uriFactory->createUri('/api/random'))) */

        return Joke::createFromJson($joke);
    }

    /**
     * {@inheritdoc}
     *
     * @throws JokeException|GuzzleException
     */
    public function getByType(string $type): Joke
    {
        $this->validateType($type);

        $joke = $this->request(sprintf('/api/type/%s/random', $type));

        return Joke::createFromJson($joke);
    }

    /**
     * {@inheritdoc}
     *
     * @throws JokeException|GuzzleException
     */
    public function getById(int $id): ?Joke
    {
        $joke = $this->request(sprintf('/api/id/%d', $id));
        if ($joke) {
            return Joke::createFromJson($joke);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws JokeException|GuzzleException
     */
    public function count(): int
    {
        $res = $this->request('/api/count');

        if (!$res['count']) {
            throw new InvalidResponseShapeException($res);
        }

        return (int) $res['count'];
    }

    /**
     * @throws JokeException
     */
    private function validateType(string $type): void
    {
        if (!in_array($type, JokeTypeInterface::TYPES)) {
            $message = sprintf(
                'Joke type "%s" does not exist!' .
                'Make sure to use one of the following types: "%s"',
                $type,
                implode(', ', JokeTypeInterface::TYPES)
            );

            throw new InvalidJokeTypeException($message);
        }
    }
}
