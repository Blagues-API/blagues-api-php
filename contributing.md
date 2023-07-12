Si vous voulez contribuer au projet, n'hésitez pas à jeter un oeil aux [issues ouvertes](https://github.com/Blagues-API/blagues-api-php/issues) et a vous en attribuer une. Il faudra ensuite forker + cloner le repo en local et installer les dépendances composer.

Voici le workflow que vous devez suivre si vous voulez que votre feature soit acceptée.

## 1 - Tests
Après avoir setup le projet localement et installé toutes les dépendances, assurez vous que les tests phpunit passent correctement **avant** et **après** vos ajouts:
```bash
cp ./phpunit.xml.dist ./phpunit.xml
php ./vendor/bin/phpunit -c ./phpunit.xml
```
Une fois votre feature terminée, assurez vous de créer des tests supplémentaires et qu'ils passent tous correctement.

## 2 - Linters
Ce projet utilise PHP Stan et PHP CS, assurez vous que les 2 passent correctement **avant** et **après** vos ajouts:
```bash
# phpstan
php ./tools/vendor/bin/phpstan analyze -c ./tools/phpstan.dist.neon

# phpcs fixer
```
