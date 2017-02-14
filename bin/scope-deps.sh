#!/usr/bin/env bash

find ./vendor -name "*.php" -exec sed -i 's/AlgoliaSearch/AlgoliaOrdersSearch/g' {} \;
find ./vendor -name "composer.json" -exec sed -i 's/AlgoliaSearch/AlgoliaOrdersSearch/g' {} \;

find ./vendor -name "*.php" -exec sed -i 's/RayRutjes/AlgoliaOrdersSearch/g' {} \;
find ./vendor -name "composer.json" -exec sed -i 's/RayRutjes/AlgoliaOrdersSearch/g' {} \;
