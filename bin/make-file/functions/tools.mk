
#Go to the bash container of the application
bash:
	@$(RUN) bash
	printf "${COLOR_COMMENT}Container removed.${COLOR_RESET}\n"
