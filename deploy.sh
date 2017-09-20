#!/bin/sh

TAG=$1

if [[ -z $TAG ]]; then
    TAG=latest
fi;

IMAGE=gitlab.toavalon.com:5000/codename-nau/web/laravel
NETWORK=nau-network

function deploy_and_check() {
    CONTAINER="nau-web-laravel-$1"
    PORT="819$1"

    printf "[Stopping] container: "
    docker stop $CONTAINER || true
    printf "[Removing] container: "
    docker rm $CONTAINER || true

    printf "[Starting] container $CONTAINER... ID: "
    docker run --name $CONTAINER -d -p $PORT:8181 --restart always --net $NETWORK -v$WEB_PATH/storage:/app/storage -v$WEB_PATH/.env:/app/.env $IMAGE:$TAG

    printf "Waiting container $CONTAINER to be up"
    until $(curl --output /dev/null --silent --head --fail http://localhost:$PORT/); do
        RUNNING=$(docker inspect --format="{{.State.Running}}" $CONTAINER 2> /dev/null)
        if [[ $RUNNING -ne true ]]; then
            echo
            echo "Container is not running... here is log:"
            docker container logs -f $CONTAINER
            exit 1
        fi
        printf '.'
        sleep 1
    done

    echo
    echo "$CONTAINER is Running on port: $PORT"
}

set -e

docker pull $IMAGE:$TAG
deploy_and_check 1
deploy_and_check 2
