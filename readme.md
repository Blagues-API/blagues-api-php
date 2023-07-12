# Blagues API

Blagues API est un api public, français, et Open-Source, qui permet a n'importe qui d'accéder et de contribuer à une vaste collections de blagues en tous genres. Ce paquet packagist permet à n'importe qui d'intéragir avec l'api très simplement en php avec une approche orientée objet.

## Authentification

Vous pouvez récupérer votre token d'authentification sur le site officiel https://www.blagues-api.fr en cliquant sur le bouton "Connexion"

## Exemples

**Voici quelques exemples simples de comment vous pouvez intéragir avec l'api.**

```php
<?php

declare(strict_types=1);

use Zuruuh\BlaguesApi\BlaguesApiFactory;

$blaguesApi = BlaguesApiFactory::create($_ENV['TOKEN']);

$joke = $blaguesApi->getRandom(); // Renvoies une instance de la classe Blagues\Model\Joke

var_dump($joke->getJoke()); // Renvoie le contenu de la blague.
var_dump($joke->getAnswer()); // Renvoie la réponse à la blague si il y en a une.
```

```php
<?php

declare(strict_types=1);

use Zuruuh\BlaguesApi\Model\Joke;
use Zuruuh\BlaguesApi\BlaguesApiFactory;

$blaguesApi = BlaguesApiFactory::create($_ENV['TOKEN']);

$joke = $blaguesApi->getById(1234);
var_dump($joke->getId()); // renvoies 1234

$joke = $blaguesApi->getRandom([Joke::TYPE_DARK]); // Cette méthode va récupérer une blague aléatoire de n'importe quel type excepté Joke::TYPE_DARK.

$joke = $blaguesApi->getByType(Joke::TYPE_DEV); // Cette méthode va récupérer une blague aléatoire de type Joke::TYPE_DEV.
```

Exemple avec Symfony
```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zuruuh\BlaguesApi\BlaguesApiFactory;

class JokeController extends AbstractController
{
    #[Route('/joke')]
    public function jokeAction(#[Autowire('%env(BLAGUES_API_TOKEN)%')] string $blaguesApiToken): Response
    {
        $blaguesApi = BlaguesApiFactory::create($blaguesApiToken);

        $joke = $blaguesApi->getRandom();

        return $this->render('template/joke.html.twig', [
            'joke' => $joke,
        ]);
    }
}
```

Exemple Symfony avec injection de dépendance + factory
```yaml
# config/services.yaml
services:
  Zuruuh\BlaguesApi\BlaguesApiInterface:
    factory: ['Zuruuh\BlaguesApi\BlaguesApiFactory', create]
    arguments: ['%env(BLAGUES_API_TOKEN)%']
```
```php
<?php
// src/Controller/JokeController

declare(strict_types=1);

namespace App\Controller;

use Blagues\BlaguesApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokeController extends AbstractController
{
    #[Route('/joke')]
    public function jokeAction(BlaguesApiInterface $blaguesApi): Response
    {
        $joke = $blaguesApi->getRandom();

        return $this->render('template/joke.html.twig', [
            'joke' => $joke,
        ]);
    }
}
```

## Contributions
Pour contribuer au projet, merci de jeter un oeil à [contributing.md](./contributing.md).
