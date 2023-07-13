<?php

declare(strict_types=1);

namespace BlaguesApi\Model;

/**
 * @internal
 * @api
 */
final class JokeCount
{
    /**
     * @api
     * @param positive-int $count
     */
    public function __construct(private int $count) {}

    /**
     * @api
     * @return positive-int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
