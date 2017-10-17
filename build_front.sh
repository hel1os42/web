#!/bin/bash

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.5/install.sh | bash && \
    export NVM_DIR="$HOME/.nvm" [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \ 
    nvm use 6.9.1 && \
    npm install && \
    npm install -g gulp && \
    npm link gulp && \
    gulp
