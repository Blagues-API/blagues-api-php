<?php

declare(strict_types=1);

namespace Blagues\Exceptions;

use Throwable;

class InvalidJokeDataException extends JokeException
{
    /**
     * @phpstan-param array<string, int|string> $data
     */
    public function __construct(array $data, ?Throwable $previous = null)
    {
        parent::__construct(
            'Invalid joke data ! Make sure the following json object is correct: ' . json_encode($data),
            400,
            $previous
        );
    }
}
