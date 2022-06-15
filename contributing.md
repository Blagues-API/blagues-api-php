If you want to contribute to this project, check and claim an open issue (or open a new one), and fork + clone the project.  
You will then be able to create your feature, but make sure you follow these steps before:

## 1 - Tests
After installing all the dependencies using composer, make sure all your tests are passing, before and after completing your feature:
```shell
cp ./phpunit.xml.dist ./phpunit.xml
php ./vendor/bin/phpunit -c ./phpunit.xml
```
When you are done coding your feature, make sure to create new tests to ensure everything works properly.

## 2 - Linters
This project uses both Phpstan and Phpcs, make sure both are passing before and after your new feature:
```shell
# phpstan
cp ./phpstan.neon.dist ./phpstan.neon
php ./vendor/bin/phpstan analyze -c ./phpstan.neon

# phpcs
cp ./phpcs.xml.dist ./phpcs.xml
php ./vendor/bin/phpcs --standard=phpcs.xml
```
