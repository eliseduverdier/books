PHONY:

csfix:
	./app/vendor/bin/php-cs-fixer fix app/src/

tests:
	php app/tests.php
