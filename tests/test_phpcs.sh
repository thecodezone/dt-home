#!/bin/bash

set -e

cd "$(dirname "${BASH_SOURCE[0]}")/../"

if [ "$(php -r 'echo version_compare( phpversion(), "8.0", ">=" ) ? 1 : 0;')" != 1 ] ; then
    vendor/bin/phpcs dt-home.php
    exit
fi

eval vendor/bin/phpcs
