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
	. ./build/wc-order-search-admin

cd ./build
zip -r ./wc-order-search-admin.zip ./wc-order-search-admin

