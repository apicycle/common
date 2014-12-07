#!/bin/bash
SCRIPT_DIR=$(dirname $(readlink -f "$0"))
PROJECT_DIR=$(dirname $(dirname $(dirname $SCRIPT_DIR)))
PHPMD_CLEAN_CODE_RULESET="$PROJECT_DIR/tests/_support/phpmd/cleancode.xml"
CODECEPTION_CONFIG="$PROJECT_DIR/codeception.yml"

php $PROJECT_DIR/vendor/bin/phploc $PROJECT_DIR/src
php $PROJECT_DIR/vendor/bin/phpmd $PROJECT_DIR/src text codesize,unusedcode,controversial,naming,design,$PHPMD_CLEAN_CODE_RULESET
php $PROJECT_DIR/vendor/bin/phpcs --standard=PSR1,PSR2 $PROJECT_DIR/src
php $PROJECT_DIR/vendor/bin/codecept run --coverage --coverage-xml --coverage-html --config=$CODECEPTION_CONFIG