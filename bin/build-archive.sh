#!/usr/bin/env bash

rm -rf ./build

mkdir ./build
mkdir ./build/wc-orders-search-algolia

cp -R ./assets ./build/wc-orders-search-algolia
cp -R ./inc ./build/wc-orders-search-algolia
cp -R ./libs ./build/wc-orders-search-algolia
cp -R ./languages ./build/wc-orders-search-algolia
cp wc-orders-search-algolia.php ./build/wc-orders-search-algolia

cd ./build
zip -r wc-orders-search-algolia.zip wc-orders-search-algolia
rm -rf ./wc-orders-search-algolia
cd ..


