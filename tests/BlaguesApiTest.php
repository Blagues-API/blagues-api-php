<?php

namespace Test;

use Blagues\BlaguesApi;
use Blagues\Exceptions\InvalidJokeDataException;
use Blagues\Exceptions\JokeException;
use Blagues\Models\Joke;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BlaguesApiTest extends TestCase
{
    private static BlaguesApi $api;

    public static function setUpBeforeClass(): void
    {
        $env = Dotenv::createImmutable((string) realpath(__DIR__ . '/../'), '.env');
        $env->safeLoad();
        self::$api = new BlaguesApi($_ENV['BLAGUES_API_TOKEN']);

        parent::setUpBeforeClass();
    }

    public function testRandomJoke(): void
    {
        $joke = self::$api->getRandom();

        $this->assertInstanceOf(Joke::class, $joke);
        $this->assertIsInt($joke->getId());
        $this->assertIsString($joke->getType());
        $this->assertIsString($joke->getJoke());
        $this->assertIsString($joke->getAnswer());
    }

    public function testJokeById(): void
    {
        $joke = self::$api->getById(1432);

        $this->assertInstanceOf(Joke::class, $joke);
        $this->assertSame(1432, $joke->getId());
        $this->assertSame(Joke::TYPE_DIRTY, $joke->getType());
        $this->assertSame("Comment appelle-t-on une douche qui n'a pas d'eau ?", $joke->getJoke());
        $this->assertSame('Une duche', $joke->getAnswer());
    }

    public function testJokeTypes(): void
    {
        foreach(Joke::TYPES as $type) {
            $joke = self::$api->getByType($type);

            $this->assertInstanceOf(Joke::class, $joke);
            $this->assertSame($type, $joke->getType());
            $this->assertIsInt($joke->getId());
            $this->assertIsString($joke->getType());
            $this->assertIsString($joke->getJoke());
            $this->assertIsString($joke->getAnswer());
        }
    }

    public function testJokeGeneration(): void
    {
        try {
            Joke::createFromJson([
                'id' => 123,
                'type' => Joke::TYPE_DEV,
                'joke' => 'text',
            ]);
        } catch (JokeException $e) {
            $this->assertInstanceOf(InvalidJokeDataException::class, $e);
        }

        $joke = [
            'id' => 123,
            'type' => Joke::TYPE_DEV,
            'joke' => 'text',
            'answer' => 'text',
        ];

        $this->assertInstanceOf(Joke::class, Joke::createFromJson($joke));
    }
}