#!/usr/bin/env bash

rm -rf ./libs
cp -R vendor libs

MY_NAMESPACE="AlgoliaOrdersSearch"

find ./libs -name "*.php" -exec sed -i "s/AlgoliaSearch/${MY_NAMESPACE}/g" {} \;
find ./libs -name "composer.json" -exec sed -i "s/AlgoliaSearch/${MY_NAMESPACE}/g" {} \;
mv ./libs/algolia/algoliasearch-client-php/src/AlgoliaSearch ./libs/algolia/algoliasearch-client-php/src/${MY_NAMESPACE} 2>/dev/null

find ./libs -name "*.php" -exec sed -i "s/RayRutjes/${MY_NAMESPACE}/g" {} \;
find ./libs -name "composer.json" -exec sed -i "s/RayRutjes/${MY_NAMESPACE}/g" {} \;
