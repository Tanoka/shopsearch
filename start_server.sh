#!/bin/bash

docker build -t myproduct:1.0 .

docker run --rm -it -p 8000:8000 myproduct:1.0