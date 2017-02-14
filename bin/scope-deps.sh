#!/usr/bin/env bash

# Todo: abstract this to make it more re-usable.

find ./vendor -name "*.php" -exec sed -i 's/AlgoliaSearch/AlgoliaOrdersSearch/g' {} \;
find ./vendor -name "composer.json" -exec sed -i 's/AlgoliaSearch/AlgoliaOrdersSearch/g' {} \;
mv ./vendor/algolia/algoliasearch-client-php/src/AlgoliaSearch ./vendor/algolia/algoliasearch-client-php/src/AlgoliaOrdersSearch 2>/dev/null

find ./vendor -name "*.php" -exec sed -i 's/RayRutjes/AlgoliaOrdersSearch/g' {} \;
find ./vendor -name "composer.json" -exec sed -i 's/RayRutjes/AlgoliaOrdersSearch/g' {} \;
