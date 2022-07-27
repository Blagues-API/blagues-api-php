<?php

declare(strict_types=1);

namespace Blagues;

interface BlaguesApiFactoryInterface
{
    public static function create(string $authToken): BlaguesApiInterface;
}
