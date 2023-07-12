/* <?php

declare(strict_types=1);

namespace Zuruuh\BlaguesApi\Tests;

use Blagues\BlaguesApi;
use Blagues\BlaguesApiFactory;
use Blagues\BlaguesApiInterface;
use Blagues\Exception\InvalidJokeDataException;
use Blagues\Exception\JokeException;
use Blagues\Model\Joke;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BlaguesApiTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $env = Dotenv::createImmutable((string) realpath(__DIR__ . '/../'), '.env');
        $env->safeLoad();

        parent::setUpBeforeClass();
    }

    public function testRandomJoke(): void
    {
        $joke = $this->getApiMock()->getRandom();

        $this->assertInstanceOf(Joke::class, $joke);
        $this->assertIsInt($joke->getId());
        $this->assertIsString($joke->getType());
        $this->assertIsString($joke->getJoke());
        $this->assertIsString($joke->getAnswer());
    }

    public function testJokeById(): void
    {
        $joke = $this->getApiMock()->getById(1432);

        $this->assertNotNull($joke);
        $this->assertInstanceOf(Joke::class, $joke);
        $this->assertSame(1432, $joke->getId());
        $this->assertSame(Joke::TYPE_DIRTY, $joke->getType());
        $this->assertSame("Comment appelle-t-on une douche qui n'a pas d'eau ?", $joke->getJoke());
        $this->assertSame('Une duche', $joke->getAnswer());
    }

    public function testCount(): void
    {
        $count = $this->getApiMock()->count();

        $this->assertGreaterThan(0, $count);
    }

    public function testJokeTypes(): void
    {
        foreach (Joke::TYPES as $type) {
            $joke = $this->getApiMock()->getByType($type);

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

    public function testApiFactory(): void
    {
        $api = BlaguesApiFactory::create('coincoin');

        $this->assertInstanceOf(BlaguesApiInterface::class, $api);
        $this->assertInstanceOf(BlaguesApi::class, $api);
    }

    /**
     * @return array<string, mixed>[]
     */
    private static function getJokesMocks(): array
    {
        return [
            'getRandom' => [
                'id' => 1,
                'joke' => 'coincoin',
                'answer' => 'pouet',
                'type' => Joke::TYPE_DEV,
            ],
            'getById' => [
                'id' => 1432,
                'type' => Joke::TYPE_DIRTY,
                'joke' => 'Comment appelle-t-on une douche qui n\'a pas d\'eau ?',
                'answer' => 'Une duche',
            ],
            'getByType' => fn (string $type) => new Joke(1, $type, 'coincoin', 'pouet'),
            'count' => 1700,
        ];
    }

    private function getApiMock(): BlaguesApi
    {
        $api = $this->createMock(BlaguesApi::class);

        foreach (self::getJokesMocks() as $methodName => $returnValue) {
            $method = $api->method($methodName);

            if (is_callable($returnValue)) {
                $method->willReturnCallback($returnValue);
            } elseif (is_array($returnValue)) {
                try {
                    /** @phpstan-ignore-next-line */
                    $joke = Joke::createFromJson($returnValue);

                    $method->willReturn($joke);
                } catch (JokeException $e) {
                    $method->willReturn($returnValue);
                }
            } else {
                $method->willReturn($returnValue);
            }
        }

        return $api;
    }
}
*/
