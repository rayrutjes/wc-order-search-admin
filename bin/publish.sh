#!/bin/bash

set -eu

readonly CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [ "$CURRENT_BRANCH" != master ]; then
  echo "You must be on 'master' branch to publish a release, aborting..."
  exit 1
fi

if ! git diff-index --quiet HEAD --; then
  echo "Working tree is not clean, aborting..."
  exit 1
fi

readonly PACKAGE_VERSION=$(< package.json grep version \
  | head -1 \
  | awk -F: '{ print $2 }' \
  | gsed 's/[",]//g' \
  | tr -d '[:space:]')

git tag "v$PACKAGE_VERSION"
git push --tags

./bin/sync-wp-org.sh \
	--plugin-name="wc-order-search-admin" \
	--git-repo="https://github.com/rayrutjes/wc-order-search-admin" \
	--svn-user=rayrutjes

echo "Pushed package to wordpress.org, and also pushed 'v$PACKAGE_VERSION' tag to git repository."
