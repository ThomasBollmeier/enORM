#!/bin/bash

PHP_HOME=/opt/lampp

$PHP_HOME/bin/phpunit --include-path ../src $1
