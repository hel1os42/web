#!/bin/bash

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.5/install.sh | bash && \
    export NVM_DIR="/root/.nvm" && \
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
    nvm use system && \
    npm uninstall -g gulp && \
    npm config delete prefix && \
    nvm use 6.9.1 --delete-prefix && \
    nvm install 6.9.1 --delete-prefix && \
    npm install && \
    npm install -g gulp && \
    npm link gulp && \
    gulp
