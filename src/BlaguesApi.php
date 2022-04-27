<?php

namespace Blagues;

use Blagues\Exceptions\InvalidJokeDataException;
use Blagues\Exceptions\InvalidJokeTypeException;
use Blagues\Exceptions\InvalidTokenException;
use Blagues\Exceptions\JokeException;
use Blagues\Models\Joke;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class BlaguesApi
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
            if ($e->getResponse()->getStatusCode() === 401) {
                throw new InvalidTokenException('Invalid Auth Token provided! Make sure you passed the correct one!');
            }

            throw $e;
        }

        $json = (string) $res->getBody();
        $joke = json_decode($json, true);

        if (!is_array($joke)) {
            throw new JokeException(
                'Invalid server response! Please report this is a new issue on this package\'s git repository.'
            );
        }

        return $joke;
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
     * @throws JokeException
     */
    public function getByType(string $type): Joke
    {
        $this->validateType($type);

        $joke = $this->request(sprintf('/api/type/%s/random', $type));

        return Joke::createFromJson($joke);
    }

    /**
     * @throws JokeException
     */
    public function getById(int $id): Joke
    {
        $joke = $this->request(sprintf('/api/id/%d', $id));

        return Joke::createFromJson($joke);
    }

    /**
     * @throws JokeException
     */
    private function validateType(string $type): void
    {
        if (!in_array($type, Joke::TYPES)) {
            throw new InvalidJokeTypeException('Joke type "' . $type . '" does not exist! Make sure to use one of the following types: ' . implode(', ', Joke::TYPES));
        }
    }
}