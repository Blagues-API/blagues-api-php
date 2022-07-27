<?php

declare(strict_types=1);

namespace Blagues;

class BlaguesApiFactory implements BlaguesApiFactoryInterface
{
    public static function create(string $authToken = ''): BlaguesApiInterface
    {
        return new BlaguesApi($authToken);
    }
}
