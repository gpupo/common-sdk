## Run phpunit testcases
test@phpunit:
	${VENDOR_BIN}/phpunit --testdox

## Run in travis execution
test@travis-script: install
test@travis-script:
	APP_ENV=test ${VENDOR_BIN}/phpunit --testdox
