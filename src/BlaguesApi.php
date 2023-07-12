<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Zuruuh\BlaguesApi\Exception\InvalidJokeTypeException;
use Zuruuh\BlaguesApi\Exception\JokeException;
use Zuruuh\BlaguesApi\Http\HttpClient;
use Zuruuh\BlaguesApi\JokeTypes;
use Zuruuh\BlaguesApi\Model\Joke;
use Zuruuh\BlaguesApi\Model\JokeCount;

/**
 * @immutable
 * @api
 */
final class BlaguesApi implements BlaguesApiInterface
{
    private HttpClient $httpClient;

    /**
     * @param non-empty-string $authToken
     */
    public function __construct(
        string $authToken,
        ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private UriFactoryInterface $uriFactory,
        private SerializerInterface $serializer
    ) {
        $this->httpClient = new HttpClient($httpClient, $authToken);
    }

    /**
     * {@inheritdoc}
     */
    public function getRandom(array $disallowed = []): Joke
    {
        if (count($disallowed) === count(JokeTypes::TYPES)) {
            throw new InvalidJokeTypeException('You cannot disable all joke types !');
        }

        foreach ($disallowed as $type) {
            $this->validateType($type);
        }

        /* $joke = $this->request('/api/random?disallow=' . $query); */
        $query = '';
        foreach ($disallowed as $type) {
            $query .= "disallow=$type&";
        }

        $request = $this
            ->requestFactory
            ->createRequest(
                'GET',
                $this->uriFactory->createUri('/api/random')->withQuery($query)
            );

        $responseContent = $this->httpClient->sendRequest($request)->getBody()->getContents();

        return $this->deserializeJokeFromJsonContent($responseContent);
    }

    /**
     * {@inheritdoc}
     */
    public function getByType(string $type): Joke
    {
        $this->validateType($type);

        $request = $this
            ->requestFactory
            ->createRequest(
                'GET',
                $this->uriFactory->createUri("/api/type/$type/random")
            );

        $responseContent = $this->httpClient->sendRequest($request)->getBody()->getContents();

        return $this->deserializeJokeFromJsonContent($responseContent);
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): ?Joke
    {
        $request = $this
            ->requestFactory
            ->createRequest(
                'GET',
                $this->uriFactory->createUri("/api/id/$id")
            );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() === 404) {
            return null;
        }

        return $this->deserializeJokeFromJsonContent($response->getBody()->getContents());
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        $request = $this
            ->requestFactory
            ->createRequest(
                'GET',
                $this->uriFactory->createUri("/api/count")
            );

        $responseContent = $this->httpClient->sendRequest($request)->getBody()->getContents();

        try {
            return $this->serializer->deserialize(
                $responseContent,
                JokeCount::class,
                'json'
            )->getCount();
        } catch (PartialDenormalizationException $exception) {
            throw new JokeException(
                'An error has occured while creating the joke count object! ' .
                    'This might be related to the serializer implementation you used',
                previous: $exception
            );
        }
    }

    /**
     * @throws JokeException
     */
    private function validateType(string $type): void
    {
        if (!in_array($type, JokeTypes::TYPES, true)) {
            $message = sprintf(
                'Joke type "%s" does not exist!' .
                'Make sure to use one of the following types: "%s"',
                $type,
                implode(', ', JokeTypes::TYPES)
            );

            throw new InvalidJokeTypeException($message);
        }
    }

    /**
     * @throws JokeException
     */
    private function deserializeJokeFromJsonContent(string $content): Joke
    {
        try {
            return $this->serializer->deserialize(
                $content,
                Joke::class,
                'json',
                [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true]
            );
        } catch (PartialDenormalizationException $exception) {
            throw new JokeException(
                'An error has occured while creating the joke object! ' .
                    'This might be related to the serializer implementation you used',
                previous: $exception
            );
        }

    }
}
