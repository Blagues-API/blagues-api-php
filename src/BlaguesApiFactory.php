<?php

declare(strict_types=1);

namespace Blagues;

class BlaguesApiFactory
{
    public static function create(string $authToken): BlaguesApiInterface
    {
        return new BlaguesApi($authToken);
    }
}
