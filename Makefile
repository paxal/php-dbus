PHP ?= php7.4
COMPOSER ?= $(PHP) $(shell which composer)

test: phpstan phpcs

fix: phpcbf

vendor/bin/phpstan vendor/bin/phpcs vendor/bin/phpcbf: vendor/autoload.php

vendor/autoload.php: composer.lock
	$(COMPOSER) install

phpstan: vendor/bin/phpstan
	$(PHP) vendor/bin/phpstan analyse -l max lib -a include/dbus-php.php

phpcs phpcbf: vendor/bin/$@
	$(PHP) vendor/bin/$@
