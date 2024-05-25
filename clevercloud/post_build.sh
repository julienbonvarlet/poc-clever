#!/bin/bash -l
set -euo pipefail

php /usr/host/bin/composer-2.phar install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
php /usr/host/bin/composer-2.phar dump-autoload --classmap-authoritative --no-dev; \
php /usr/host/bin/composer-2.phar dump-env prod; \
php /usr/host/bin/composer-2.phar run-script --no-dev post-install-cmd; \
php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing; \
php bin/console cache:clear --env=prod --no-debug

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
varnish_conf_file=${SCRIPT_DIR}/varnish.vcl

envs=`printenv`
for env in $envs
do
    IFS== read name value <<< "$env"
    sed -i "s|\${${name}}|${value}|g" $varnish_conf_file
done
