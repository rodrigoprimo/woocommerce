#!/usr/bin/env bash

if [[ ${RUN_E2E_TESTS} == 1 ]]; then
	node_modules/.bin/cross-env NODE_CONFIG_DIR='./tests/e2e-tests/config' BABEL_ENV=commonjs HEADLESS=1 node_modules/.bin/mocha "tests/e2e-tests" --compilers js:babel-register --recursive
else
	if [[ ${RUN_CODE_COVERAGE} == 1 ]]; then
		phpunit -c phpunit.xml --coverage-clover=coverage.clover
	else
		phpunit -c phpunit.xml
	fi
fi
