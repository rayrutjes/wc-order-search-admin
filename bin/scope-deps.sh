#!/usr/bin/env bash

rm -rf ./libs
cp -R vendor libs
rm -rf ./libs/composer/installers

MY_NAMESPACE="AlgoliaWooCommerceOrderSearchAdmin"

find ./libs -name "*.php" -exec sed -i "s/AlgoliaSearch/${MY_NAMESPACE}/g" {} \;
find ./libs -name "composer.json" -exec sed -i "s/AlgoliaSearch/${MY_NAMESPACE}/g" {} \;
mv ./libs/algolia/algoliasearch-client-php/src/AlgoliaSearch ./libs/algolia/algoliasearch-client-php/src/${MY_NAMESPACE} 2>/dev/null

find ./libs -name "*.php" -exec sed -i "s/Algolia\\\Index/${MY_NAMESPACE}\\\Index/g" {} \;
find ./libs -name "autoload_psr4.php" -exec sed -i "s/Algolia/${MY_NAMESPACE}/g" {} \;
find ./libs -name "composer.json" -exec sed -i "s/Algolia/${MY_NAMESPACE}/g" {} \;

php ./bin/name.php
