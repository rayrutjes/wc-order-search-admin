#!/usr/bin/env bash

set -eu

bin/php-scoper add-prefix --output-dir="libs" --prefix="WC_Order_Search_Admin" --force
composer dump-autoload --working-dir="libs" --classmap-authoritative
