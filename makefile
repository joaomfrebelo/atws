all: style analyse test cleandoc doc

test:
	./vendor/bin/phpunit tests/

doc:
	./tools/phploc.bat ./ --suffix php --exclude "./vendor"  --count-tests --log-xml ./logs/phploc.xml
	./tools/phpdox.bat

analyse: 
	./vendor/bin/phpstan analyse

style:
	./vendor/bin/phpcbf
	./vendor/bin/phpcs

cleandoc:
	rm --force -r ./docs/html/*
	rm --force -r ./docs/build/phpdox/*
