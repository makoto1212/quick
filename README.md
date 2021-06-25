# quick

## setup
php composer.phar install  
sh ./shell/install.sh

## formatter
./vendor/bin/phpcs --standard=PSR12 --report=code --colors ./app  
./vendor/bin/phpcbf --standard=PSR12 ./app
