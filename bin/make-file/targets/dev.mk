

## Run Phan checkup
dev@phan:
	${COMPOSER_BIN}/phan --config-file config/phan.php

## Psalm - a static analysis
dev@psalm:
	${VENDOR_BIN}/psalm --show-info=false

## Update make file
dev@selfupdate:
	cp -f vendor/gpupo/common/Makefile Makefile
