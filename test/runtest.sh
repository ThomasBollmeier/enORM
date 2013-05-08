#!/bin/bash

PHP_HOME=$HOME/opt/php5

$PHP_HOME/bin/phpunit --include-path ../src $1
