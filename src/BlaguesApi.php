<?php

declare(strict_types=1);

namespace Blagues;

use Blagues\Exceptions\ApiUnavailableException;
use Blagues\Exceptions\InvalidJokeTypeException;
use Blagues\Exceptions\InvalidResponseShapeException;
use Blagues\Exceptions\InvalidTokenException;
use Blagues\Exceptions\JokeException;
use Blagues\Models\Joke;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class BlaguesApi implements BlaguesApiInterface
{
    private Client $httpClient;

    public function __construct(string $authToken)
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://www.blagues-api.fr/',
            'timeout' => 10,
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $authToken
            ]
        ]);
    }

    /**
     * @throws JokeException|GuzzleException
     *
     * @phpstan-return array<string, int|string>
     */
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
            if ($statusCode > 500) {
                throw new ApiUnavailableException($e);
            }

            throw $e;
        }

        $json = (string) $res->getBody();
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new JokeException(
                'Invalid server response! Please report this is a new issue on this package\'s git repository.'
            );
        }

        return $data;
    }

    /**
     * @phpstan-param value-of<Joke::TYPES>[] $disallowed
     *
     * @throws JokeException|GuzzleException
     */
    public function getRandom(array $disallowed = []): Joke
    {
        $query = '';
        if (!empty($disallowed)) {
            if (count($disallowed) === count(Joke::TYPES)) {
                throw new InvalidJokeTypeException('You cannot disable all joke types !');
            }

            foreach ($disallowed as $type) {
                $this->validateType($type);
            }

            $query = implode('&disallow=', $disallowed);
        }

        $joke = $this->request('/api/random' . $query);

        return Joke::createFromJson($joke);
    }

    /**
     * @phpstan-param value-of<Joke::TYPES> $type
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
        if (!in_array($type, Joke::TYPES)) {
            $message = sprintf('Joke type "%s" does not exist! Make sure to use one of the following types: %s', $type, implode(', ', Joke::TYPES));

            throw new InvalidJokeTypeException($message);
        }
    }
}
