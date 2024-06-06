#!/bin/bash

# Get the package version from the package.json file.

PACKAGE_VERSION=$(echo jq -r ".version" ../../package.json)

# Set environment variables on $GITHUB_ENV.

echo "PACKAGE_VERSION=$(echo $THEME_VERSION)" >> $GITHUB_ENV
