#!/bin/bash

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.5/install.sh | bash && \
    export NVM_DIR="/root/.nvm" && \
    source "$NVM_DIR/nvm.sh" && \ 
    npm config delete prefix && \
    nvm install 6.9.1 --delete-prefix && \
    nvm debug && \
    npm install && \
    npm install -g gulp && \
    npm link gulp && \
    gulp
