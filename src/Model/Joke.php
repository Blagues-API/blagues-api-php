<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Model;

use Zuruuh\BlaguesApi\JokeTypes;

/**
 * @immutable
 */
final class Joke
{
    /**
     * @api
     * @param positive-int $id
     * @param value-of<JokeTypes::TYPES> $type
     * @param non-empty-string $joke
     * @param non-empty-string $answer
     */
    public function __construct(
        private int $id,
        private string $type,
        private string $joke,
        private string $answer
    ) {}

    /**
     * @api
     * @return positive-int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @api
     * @return value-of<JokeTypes::TYPES>
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @api
     * @return non-empty-string
     */
    public function getJoke(): string
    {
        return $this->joke;
    }

    /**
     * @api
     * @return non-empty-string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }
}
