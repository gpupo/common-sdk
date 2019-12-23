VENDOR_BIN=./vendor/bin
CORE_FILES=./vendor/gpupo/common/bin/make-file/
COMMON_SDK_BIN=./vendor/gpupo/common-sdk/bin

#Common
ifneq ($(wildcard ${CORE_FILES}/*),)
	include ${CORE_FILES}/variables.mk
	include ${CORE_FILES}/define.mk
	include ${CORE_FILES}/help.mk
endif
