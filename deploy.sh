#!/bin/sh

TAG=$1

if [[ -z ${TAG} ]]; then
    TAG=latest
fi;

IMAGE=gitlab.toavalon.com:5000/codename-nau/web/laravel

docker image pull ${IMAGE}:${TAG}
docker service update ${STACK}_web --force --detach=false
