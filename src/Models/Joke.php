<?php

declare(strict_types=1);

namespace Blagues\Models;

use Blagues\JokeJsonAdapterTrait;
use Blagues\JokeTypeInterface;
use JsonSerializable;

class Joke implements JsonSerializable, JokeTypeInterface
{
    use JokeJsonAdapterTrait;

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
}
