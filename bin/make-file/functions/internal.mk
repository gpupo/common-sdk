
__header:
	if [ ! -f /.dockerenv ]; then \
		printf "\n\n!!! ${COLOR_ERROR}This target is only available for execution inside a container!${COLOR_RESET}\n\n\n"; \
		$(MAKE) help; \
		exit 1; \
	else \
		printf "\n";\
	fi;

__bottom:
	printf "\nTarget ${COLOR_COMMENT}Done!${COLOR_RESET}\n";
