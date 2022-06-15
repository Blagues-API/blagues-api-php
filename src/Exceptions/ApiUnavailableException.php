<?php

declare(strict_types=1);

namespace Blagues\Exceptions;

use Throwable;

class ApiUnavailableException extends JokeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Blagues API is currently unavailable, please try again later!", 503, $previous);
    }
}
