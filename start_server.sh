#!/bin/bash

docker build -t mytheresa:1.0 .

docker run --rm -it -p 8000:8000 mytheresa:1.0 