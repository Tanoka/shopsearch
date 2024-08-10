#!/bin/bash

docker build -t myproduct:1.0 .

# docker run --rm -it -p 8000:8000 myproduct:1.0 vendor/bin/phpunit

# export XDEBUG_MODE=coverage
docker run --rm -it -p 8000:8000 -e XDEBUG_MODE=coverage myproduct:1.0 vendor/bin/phpunit --coverage-text
