# Blagues API

Blagues API is a french public api which allows anyone to create and access multiple hundreds of jokes.
This packages allows anyone to use easily this api using an OO approach.

## Examples

**Here are some simple examples you can use to interact with the api.**
```php
<?php

use Blagues\BlaguesApi;

class Main
{
    public static main(): void
    {
        $blaguesApi = new BlaguesApi($_ENV['TOKEN']);

        $joke = $blaguesApi->getRandom(); // Returns an object of class Blagues\Models\Joke

        var_dump($joke->getJoke()); // This returns the actual joke.
        var_dump($joke->getAnswer()); // And this return the answer to the joke if there is one.
    }
}
```

```php
<?php

use Blagues\Models\Joke;
use Blagues\BlaguesApi;

$blaguesApi = new BlaguesApi($_ENV['TOKEN']);

$joke = $blaguesApi->getById(1234);
var_dump($joke->getId()); // returns 1234

$joke = $blaguesApi->getRandom([Joke::TYPE_DARK]); // This will fetch any type of joke except for Joke::TYPE_DARK

$joke = $blaguesApi->getByType(Joke::TYPE_DEV); // This will fetch a random joke of type Joke::TYPE_DEV
```

Example using Symfony
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Blagues\BlaguesApi;

class JokeController extends AbstractController
{
    private ParameterBagInterface $parametersBag;

    public function __construct(ParameterBagInterface $parametersBag)
    {
        $this->parametersBag = $parametersBag;
    }

    #[Route('/joke')]
    public function jokeAction(): Response
    {
        $blaguesApi = new BlaguesApi($this->parametersBag->get('BLAGUES_API_TOKEN'));

        $joke = $blaguesApi->getRandom();

        return $this->render('template/joke.html.twig', [
            "joke" => $joke,
        ]);
    }
}
```
