#!/bin/bash

IMAGE="stairbun:latest"

# if does not exist, build the image
if [[ "$(docker images -q $IMAGE 2> /dev/null)" == "" ]]; then
    docker build -t $IMAGE .
fi

# run the container, mounting the current directory to /home/bun/app and run interactively
docker run --rm -it -v ${PWD}://home/bun/app $IMAGE