<?php

declare(strict_types=1);

namespace BlaguesApi;

final class JokeTypes
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

    /**
     * @api
     */
    private function __construct() {}
}
