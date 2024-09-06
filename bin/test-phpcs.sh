set -e

cd "$(dirname "${BASH_SOURCE[0]}")/../"

composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev wp-coding-standards/wpcs:"^3.0"

if [ "$(php -r 'echo version_compare( phpversion(), "7.0", ">=" ) ? 1 : 0;')" != 1 ] ; then
    vendor/bin/phpcs dt-plugin.php
    exit
fi

eval vendor/bin/phpcs
