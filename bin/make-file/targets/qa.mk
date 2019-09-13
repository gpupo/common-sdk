## Apply CS fixers and QA watchers
qa@all: cs phploc phpstan phpmd phan psalm

## Apply Php CS fixer and PHPCBF fix rules
qa@cs: php-cs-fixer phpcbf

## Apply Php CS fixer rules
qa@php-cs-fixer:
	 ${COMPOSER_BIN}/php-cs-fixer fix --verbose

## Apply PHPCBF fix rules
qa@phpcbf:
	 ${COMPOSER_BIN}/phpcbf -i;
	 ${COMPOSER_BIN}/phpcbf -v

## Run PHP Mess Detector on the test code
qa@phpmd:
	${COMPOSER_BIN}/phpmd src text codesize,unusedcode,naming,design --exclude vendor,tests,Resources

## Measure project size using PHPLOC and print human readable output
qa@phploc:
	mkdir -p Resources/statistics;
	printf "${COLOR_COMMENT}Running PHP Lines of code statistics on library folder${COLOR_RESET}\n"
	${COMPOSER_BIN}/phploc --count-tests src/ tests/ | grep -v Warning | tee Resources/statistics/lines-of-codes.txt

## PHP Static Analysis Tool
qa@phpstan:
	printf "${COLOR_COMMENT}Running PHP Static Analysis Tool${COLOR_RESET}\n"
	${COMPOSER_BIN}/phpstan analyse -c config/phpstan.neon -l 4 src

## Run Phan checkup
qa@phan:
	${COMPOSER_BIN}/phan --config-file config/phan.php

## Psalm - a static analysis
qa@psalm:
	${VENDOR_BIN}/psalm --show-info=false
