rsync \
	--compress \
	--recursive \
	--delete \
	--delete-excluded \
	--force \
	--archive \
	--exclude-from .distignore \
	. ./build
