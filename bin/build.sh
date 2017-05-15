set -eu

mkdir -p ./build

rsync \
	--compress \
	--recursive \
	--delete \
	--delete-excluded \
	--force \
	--archive \
	--exclude-from .distignore \
	. ./build/algolia-woocommerce-order-search-admin

cd ./build
zip -r ./algolia-woocommerce-order-search-admin.zip ./algolia-woocommerce-order-search-admin

