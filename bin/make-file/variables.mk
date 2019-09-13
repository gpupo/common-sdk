DC=docker-compose
RUN=$(DC) run --rm php-fpm
COMPOSER_BIN=~/.composer/vendor/bin
COMMON_SDK_BIN=~/.composer/vendor/gpupo/common-sdk/bin
VENDOR_BIN=./vendor/bin
## Colors
COLOR_RESET   = \033[0m
COLOR_INFO  = \033[32m
COLOR_COMMENT = \033[33m
SHELL := /bin/bash
COLOR_ERROR=\033[31m

## VERBOSE=-vv
VERBOSE=
