.DEFAULT_GOAL := help

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: help


##
## Tests & QA
## -------
##

phpcs: 						## Run PHPCS QA
	php -d memory_limit=-1 vendor/bin/php-cs-fixer fix --config=.php_cs.dist.php --dry-run --diff --verbose --allow-risky=yes

phpunit:		            ## Run phpunit tests suite
	php -d memory_limit=-1 vendor/bin/phpunit -c .phpunit.dist.xml

psalm: 						## Run Psalm static code analysis
	php -d memory_limit=-1 vendor/bin/psalm -c .psalm.xml ${c}

quality: phpcs				## Run all quality checks

tests: phpunit psalm 		## Run all tests suites

.PHONY: phpcs phpunit psalm quality tests
