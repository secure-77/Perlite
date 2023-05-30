#!/bin/bash

# update dependencies
composer update

# update highlight js
cp perlite/vendor/npm-asset/highlightjs--cdn-assets/highlight.min.js perlite/.js/
cp perlite/vendor/npm-asset/highlightjs--cdn-assets/styles/atom-one-dark.min.css perlite/.styles/

# update jquery
cp perlite/vendor/npm-asset/jquery/dist/jquery.min.js perlite/.js/

# update mermaid
cp perlite/vendor/npm-asset/mermaid/dist/mermaid.min.js perlite/.js/
cp perlite/vendor/npm-asset/mermaid/dist/mermaid.min.js.map perlite/.js/

# update katex
cp perlite/vendor/npm-asset/katex/dist/katex.min.js perlite/.js/
cp perlite/vendor/npm-asset/katex/dist/contrib/auto-render.min.js perlite/.js/
cp perlite/vendor/npm-asset/katex/dist/katex.min.css perlite/.styles/
cp -r perlite/vendor/npm-asset/katex/dist/fonts perlite/.styles/

# update vis-network
cp perlite/vendor/npm-asset/vis-network/dist/vis-network.min.js perlite/.js/
cp perlite/vendor/npm-asset/vis-network/dist/vis-network.min.js.map perlite/.js/
cp perlite/vendor/npm-asset/vis-network/dist/dist/vis-network.min.css perlite/.styles/

# update Parsedown
cp perlite/vendor/erusev/parsedown/Parsedown.php perlite/

# remove old container and images
docker container rm perlite;
docker container rm perlite_web;
docker container rm perlite_web_dev; 
docker image rm sec77/perlite_web:stable; 
docker image rm sec77/perlite; 
docker image rm php;

# rebuild and start
cd perlite
docker build -t sec77/perlite:latest . --network host;
cd ..;
docker-compose --file docker-compose.yml up
