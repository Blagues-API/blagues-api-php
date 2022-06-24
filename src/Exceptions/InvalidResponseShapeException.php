<?php

declare(strict_types=1);

namespace Blagues\Exceptions;

use Throwable;

class InvalidResponseShapeException extends JokeException
{
    /**
     * @param mixed $data
     */
    public function __construct($data, Throwable $previous = null)
    {
        $data = print_r($data, true);

        parent::__construct("Api returned an invalid response shape (returned \"{$data}\")! Please report this issue on https://github.com/Blagues-API/blagues-api-php/issues", 500, $previous);
    }
}
