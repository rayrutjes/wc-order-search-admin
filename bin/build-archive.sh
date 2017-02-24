#!/usr/bin/env bash

rm -rf ./build

mkdir ./build
mkdir ./build/algolia-woocommerce-order-search-admin

cp -R ./assets ./build/algolia-woocommerce-order-search-admin
cp -R ./inc ./build/algolia-woocommerce-order-search-admin
cp -R ./libs ./build/algolia-woocommerce-order-search-admin
cp -R ./languages ./build/algolia-woocommerce-order-search-admin
cp algolia-woocommerce-order-search-admin.php ./build/algolia-woocommerce-order-search-admin
cp readme.txt ./build/algolia-woocommerce-order-search-admin

cd ./build
zip -r algolia-woocommerce-order-search-admin.zip algolia-woocommerce-order-search-admin
rm -rf ./algolia-woocommerce-order-search-admin
cd ..


