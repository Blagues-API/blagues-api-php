<?php

declare(strict_types=1);

namespace Blagues;

use Blagues\Exceptions\InvalidJokeDataException;
use Blagues\Models\Joke;

trait JokeJsonAdapterTrait
{
    /**
     * @param string|array<string, int|string> $json
     *
     * @throws InvalidJokeDataException
     */
    public static function createFromJson($json): Joke
    {
        $data = is_string($json) ? json_decode($json, true) : $json;

        if (!is_array($data)) {
            throw new InvalidJokeDataException([]);
        }

        if (!array_key_exists('id', $data) ||
            !array_key_exists('type', $data) ||
            !array_key_exists('joke', $data) ||
            !array_key_exists('answer', $data)
        ) {
            throw new InvalidJokeDataException($data);
        }

        return new Joke($data['id'], $data['type'], $data['joke'], $data['answer']);
    }

    /**
     * @return array<string, string|int>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'joke' => $this->joke,
            'answer' => $this->answer,
        ];
    }
}
