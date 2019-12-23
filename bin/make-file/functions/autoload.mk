#CommonDev
ifneq ($(wildcard ${CORE_FILES}/functions/*),)
	include ${CORE_FILES}/functions/*.mk
	include ${CORE_FILES}/targets/*.mk
endif
