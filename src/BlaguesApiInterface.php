<?php

declare(strict_types=1);

namespace Blagues;

use Blagues\Models\Joke;

interface BlaguesApiInterface extends JokeTypeInterface
{
    /**
     * Get a random joke.
     *
     * @phpstan-param value-of<JokeTypeInterface::TYPES>[] $disallowed
     *                The joke types that are not allowed to be returned.
     */
    public function getRandom(array $disallowed = []): Joke;

    /**
     * Get a random joke by its type.
     *
     * @phpstan-param value-of<JokeTypeInterface::TYPES> $type
     */
    public function getByType(string $type): Joke;

    /**
     * Finds a joke by its id.
     */
    public function getById(int $id): ?Joke;

    /**
     * Returns the total count of jokes.
     */
    public function count(): int;
}
