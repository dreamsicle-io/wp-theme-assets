#!/bin/bash

# This file must have permissions to run in a GitHub action.
# To do this cross-env, use the following command from the project root:
# git update-index --chmod=+x .github/workflows/scripts/env.sh

# Get the package version from the package.json file.

PACKAGE_VERSION=$(jq -r ".version" package.json)

# Set environment variables on $GITHUB_ENV.

echo "PACKAGE_VERSION=$(echo $PACKAGE_VERSION)" >> $GITHUB_ENV
