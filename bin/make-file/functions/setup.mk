
## Setup environment
setup:
	mkdir -p Resources/statistics

## Composer Install
install:
	composer self-update
	composer install --prefer-dist

## Composer Update and register packages
update:
	rm -f *.lock
	composer update --no-scripts -n
	composer info > Resources/statistics/composer-packages.txt

## Clean temporary files
clean:
	printf "${COLOR_COMMENT}Remove temporary files${COLOR_RESET}\n"
	rm -rfv ./vendor/* ./var/* ./*.lock ./*.cache
	git checkout ./var/cache/.gitignore ./var/data/.gitignore
