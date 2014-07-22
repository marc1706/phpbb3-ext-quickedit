#!/bin/bash
#
# This file is part of the Quickedit package.
#
# @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
# @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
#
set -e
set -x

DB=$1
TRAVIS_PHP_VERSION=$2

if [ "$TRAVIS_PHP_VERSION" == "5.5" -a "$DB" == "mysqli" ]
then
	php ../marc1706/quickedit/vendor/bin/coveralls -v
fi
