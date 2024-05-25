#!/bin/bash -l
set -euo pipefail

varnish_conf_file=${APP_HOME}/clevercloud/varnish.vcl

envs=`printenv`
for env in $envs
do
    IFS== read name value <<< "$env"
    sed -i "s|\${${name}}|${value}|g" $varnish_conf_file
done
