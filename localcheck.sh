#!/usr/bin/env bash

git submodule sync --recursive
git submodule update --init --recursive
vendor/bin/phpcs --standard=guidelines/phpcs.xml --ignore=/vendor,/database,/cache,/compiled,/public,*.blade.php,/bootstrap -w --colors --report-full .
vendor/bin/phpmd . text ./guidelines/phpmd.xml --exclude vendor,database,cache,compiled,public --suffixes php
