#!/usr/bin/make
# This file is part of gpupo/common-sdk
# Created by Gilmar Pupo <contact@gpupo.com>
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
# For more information, see <https://opensource.gpupo.com/>.

# This file has settings for the Make of this project.
# Targets must exist in the bin/make-file/targets/ directory.

.SILENT:
CURRENT_DIR := $(shell pwd)

include bin/make-file/variables.mk
include bin/make-file/define.mk
include bin/make-file/help.mk

include bin/make-file/functions/*.mk
include bin/make-file/targets/*.mk

#CommonDev
ifneq ($(wildcard vendor/gpupo/common-dev/bin/make-file/targets/*),)
	include vendor/gpupo/common-dev/bin/make-file/targets/*
endif
