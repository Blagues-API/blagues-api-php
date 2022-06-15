<?php

declare(strict_types=1);

namespace Blagues\Models;

use Blagues\Exceptions\InvalidJokeDataException;
use JsonSerializable;

class Joke implements JsonSerializable
{
    public const TYPE_GLOBAL = 'global';
    public const TYPE_DEV    = 'dev';
    public const TYPE_DARK   = 'dark';
    public const TYPE_LIMIT  = 'limit';
    public const TYPE_DIRTY  = 'beauf';
    public const TYPE_BLONDS = 'blondes';

    public const TYPES = [
        self::TYPE_GLOBAL,
        self::TYPE_DEV,
        self::TYPE_DARK,
        self::TYPE_LIMIT,
        self::TYPE_DIRTY,
        self::TYPE_BLONDS,
    ];

    private int $id;

    private string $type;

    private string $joke;

    private string $answer;

    public function __construct(int $id, string $type, string $joke, string $answer)
    {
        $this->id = $id;
        $this->type = $type;
        $this->joke = $joke;
        $this->answer = $answer;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getJoke(): string
    {
        return $this->joke;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

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

        if (
            !array_key_exists('id', $data) ||
            !array_key_exists('type', $data) ||
            !array_key_exists('joke', $data) ||
            !array_key_exists('answer', $data)
        ) {
            throw new InvalidJokeDataException($data);
        }

        return new self($data['id'], $data['type'], $data['joke'], $data['answer']);
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
