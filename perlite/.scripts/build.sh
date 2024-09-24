#!/bin/bash

# update dependencies

# force update on asset-packagist
curl https://asset-packagist.org/package/npm-asset/vis-network -D - >/dev/null
curl https://asset-packagist.org/package/npm-asset/highlightjs--cdn-assets -D - >/dev/null

# composer clear-cache
composer update -v

# update highlight css
cp vendor/npm-asset/highlightjs--cdn-assets/styles/atom-one-dark.min.css ../.styles/


# update vis-network
cp vendor/npm-asset/vis-network/dist/dist/vis-network.min.css ../.styles/

# update Parsedown dependencie
cd ..
composer update -v



