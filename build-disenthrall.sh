#!/bin/bash

# run with: sudo bash ./build-disenthrall.sh  

# remove old container and images
docker container rm perlite;
docker container rm perlite_web;
docker container rm perlite_web_dev; 
docker image rm sec77/perlite_web:stable; 
docker image rm notgovernor/perlite; 
docker image rm php;

# rebuild and start
cd perlite
# docker build -t sec77/perlite:latest . --network host;
docker build -t notgovernor/perlite:latest -f Dockerfile-Disenthrall . --network host;
cd ..;
docker-compose --file docker-compose-disenthrall.yml up
