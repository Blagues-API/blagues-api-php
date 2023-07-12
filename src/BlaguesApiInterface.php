<?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi;

use Blagues\Models\Joke;

interface BlaguesApiInterface
{
    /**
     * @phpstan-param list<value-of<JokeTypeInterface::TYPES>> $disallowed
     *                The joke types that are not allowed to be returned.
     */
    public function getRandom(array $disallowed = []): Joke;

    /**
     * @phpstan-param value-of<JokeTypeInterface::TYPES> $type
     */
    public function getByType(string $type): Joke;

    /**
     * @param positive-int $id 
     */
    public function getById(int $id): ?Joke;

    /**
     * @return positive-int 
     */
    public function count(): int;
}
