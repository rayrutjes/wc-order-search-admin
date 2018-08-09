#!/usr/bin/env bash
set -e

yarn run --silent githubcontrib \
	--owner rayrutjes \
	--repo wc-order-search-admin \
	--cols 6 \
	--showlogin true \
	> CONTRIBUTORS.md
