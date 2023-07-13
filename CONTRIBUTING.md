Si vous voulez contribuer au projet, n'hésitez pas à jeter un oeil aux [issues ouvertes](https://github.com/Blagues-API/blagues-api-php/issues) et a vous en attribuer une. Il faudra ensuite forker + cloner le repo en local et installer les dépendances composer.

Voici le workflow que vous devez suivre si vous voulez que votre feature soit acceptée.

## Linters
Ce projet utilise phpstan, psalm, et php cs fixer, assurez vous que les 3 outils fonctionnent correctement **avant** et **après** vos ajouts:
```bash
composer install
composer install --working-dir=tools

php ./tools/vendor/bin/phpstan analyze -c ./tools/phpstan.dist.neon
php ./tools/vendor/bin/psalm -c ./tools/psalm.dist.xml
php ./tools/vendor/bin/php-cs-fixer fix --config ./tools/.php-cs-fixer.dist.php
```
