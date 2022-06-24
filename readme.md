# Blagues API

Blagues API est un api public, français, et Open-Source, qui permet a n'importe qui d'accéder et de contribuer à une vaste collections de blagues en tous genres. Ce paquet packagist permet à n'importe qui d'intéragir avec l'api très simplement en php avec une approche orientée objet.

## Authentification

Vous pouvez récupérer votre token d'authentification sur le site officiel https://www.blagues-api.fr en cliquant sur le bouton "Connexion"

## Exemples

**Voici quelques exemples simples de comment vous pouvez intéragir avec l'api.**

```php
<?php

use Blagues\BlaguesApi;

class Main
{
    public static main(): void
    {
        $blaguesApi = new BlaguesApi($_ENV['TOKEN']);

        $joke = $blaguesApi->getRandom(); // Renvoies une instance de la classe Blagues\Models\Joke

        var_dump($joke->getJoke()); // Renvoie le contenu de la blague.
        var_dump($joke->getAnswer()); // Renvoie la réponse à la blague si il y en a une.
    }
}
```

```php
<?php

use Blagues\Models\Joke;
use Blagues\BlaguesApi;

$blaguesApi = new BlaguesApi($_ENV['TOKEN']);

$joke = $blaguesApi->getById(1234);
var_dump($joke->getId()); // renvoies 1234

$joke = $blaguesApi->getRandom([Joke::TYPE_DARK]); // Cette méthode va récupérer une blague aléatoire de n'importe quel type excepté Joke::TYPE_DARK.

$joke = $blaguesApi->getByType(Joke::TYPE_DEV); // Cette méthode va récupérer une blague aléatoire de type Joke::TYPE_DEV.
```

Exemple avec Symfony
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

Exemple Symfony avec injection de dépendance + factory
```yaml
# config/services.yaml
services:
  Blagues\BlaguesApi:
    factory: ['@Blagues\BlaguesApiFactory', create]
```
```php
<?php
// src/Controller/JokeController

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Blagues\BlaguesApi;

class JokeController extends AbstractController
{
    #[Route('/joke')]
    public function jokeAction(BlaguesApi $blaguesApi): Response
    {
        $joke = $blaguesApi->getRandom();

        return $this->render('template/joke.html.twig', [
            "joke" => $joke,
        ]);
    }
}
```

## Contributions
Pour contribuer au projet, merci de jeter un oeil à [contributing.md](./contributing.md).
