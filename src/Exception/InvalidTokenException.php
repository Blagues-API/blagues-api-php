<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Exception;

use Throwable;

final class InvalidTokenException extends JokeException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Invalid Auth Token provided! Make sure you passed the correct one!', 401, $previous);
    }
}
