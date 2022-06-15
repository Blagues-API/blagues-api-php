<?php

declare(strict_types=1);

namespace Blagues;

use Blagues\Models\Joke;

interface BlaguesApiInterface
{
    public function __construct(string $authToken);

    /**
     * @phpstan-param value-of<Joke::TYPES>[] $disallowed
     */
    public function getRandom(array $disallowed = []): Joke;

    /**
     * @phpstan-param value-of<Joke::TYPES> $type
     */
    public function getByType(string $type): Joke;

    public function getById(int $id): ?Joke;
}
