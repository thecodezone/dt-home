#!/bin/bash
owd=$(pwd)
cd "$(dirname "$0")/.."
composer install --no-dev --no-interaction --ignore-platform-reqs
npm run build
rm -f dt-home dt-home.zip
mkdir dt-home
rsync -av --exclude={dt-home,.git,.github,.idea,.phpunit.cache,node_modules,tests,.editorconfig,.gitignore.phpunit.result.cache,babel.config.json,CODE_OF_CONDUCT.md,CONTRIBUTING.md,package.json,package-lock.json,phpcs.xml,phpunit.xml.dist,README.md,vite.config.js} . dt-home
cp -r dt-home.php dist languages resources routes src vendor vendor-scoped composer.json composer.lock composer.scoped.json composer.scoped.lock LICENSE package.json package-lock.json version-control.json SECURITY.md dt-home/
zip -r dt-home.zip dt-home
rm -rf dt-home
cd "$owd"
