#!/usr/bin/env bash
set -e

npm run --silent githubcontrib \
	--owner rayrutjes \
	--repo wc-order-search-admin \
	--cols 6 \
	--showlogin true \
	> CONTRIBUTORS.md
