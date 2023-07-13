<?php

declare(strict_types=1);

namespace BlaguesApi;

use BlaguesApi\Exception\JokeException;
use BlaguesApi\JokeTypes;
use BlaguesApi\Model\Joke;

/**
 * @api
 */
interface BlaguesApiInterface
{
    /**
     * @param list<value-of<JokeTypes::TYPES>> $disallowed The joke types that are not allowed to be returned.
     *
     * @throws JokeException
     */
    public function getRandom(array $disallowed = []): Joke;

    /**
     * @param value-of<JokeTypes::TYPES> $type
     *
     * @throws JokeException
     */
    public function getByType(string $type): Joke;

    /**
     * @param positive-int $id
     *
     * @throws JokeException
     */
    public function getById(int $id): ?Joke;

    /**
     * @return positive-int
     *
     * @throws JokeException
     */
    public function count(): int;
}
