# This file has settings for the Make of this project.
# Targets must exist in the bin/make-file/targets/ directory.

.SILENT:
CURRENT_DIR := $(shell pwd)

#CommonDev
ifneq ($(wildcard vendor/gpupo/common-dev/bin/make-file/targets/*),)
	include bin/make-file/variables.mk
	include bin/make-file/define.mk
	include bin/make-file/help.mk
	include bin/make-file/functions/*.mk
	include bin/make-file/targets/*.mk
	include vendor/gpupo/common-dev/bin/make-file/targets/*
endif

## Install vendores
install:
	composer install --prefer-dist --no-scripts
