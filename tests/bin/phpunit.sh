#!/usr/bin/env bash
if [[ ${RUN_CODE_COVERAGE} == 1 ]]; then
	phpdbg -qrr -d memory_limit=-1 $HOME/.composer/vendor/bin/phpunit -c phpunit.xml --coverage-clover=coverage.clover --exclude-group=timeout
else
	$HOME/.config/composer/vendor/bin/phpunit --version
	composer exec -v --  "phpunit -c phpunit.xml"
fi
