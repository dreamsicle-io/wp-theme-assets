#!/bin/bash

# Get PHP version that satisfies the setup-php action version keys
# by parsing the composer.json file's require.php config.

RAW_PHP_VERSION=$(jq -r ".require.php" ../../composer.json)
MAJOR_VERSION_PATTERN="^\^"
PHP_VERSION=""
if [[ $RAW_PHP_VERSION =~ $MAJOR_VERSION_PATTERN ]]; then
	PHP_VERSION=$(echo $RAW_PHP_VERSION | grep -oE "[0-9]+" | head -n 1)
else
	PHP_VERSION=$(echo $RAW_PHP_VERSION | grep -oE "[0-9]+(\.[0-9]+)?" | head -n 1)
fi

# Get the theme name from the package.json file.

THEME_NAME=$(echo jq -r ".name" ../../package.json)

# Get the theme version from the package.json file.

THEME_VERSION=$(echo jq -r ".version" ../../package.json)

# Get the node version from the .nvmrc file.

NODE_VERSION=$(echo cat ../../.nvmrc)

# Set environment variables on $GITHUB_ENV.

echo "THEME_NAME=$(echo $THEME_NAME)" >> $GITHUB_ENV
echo "THEME_VERSION=$(echo $THEME_VERSION)" >> $GITHUB_ENV
echo "PHP_VERSION=$(echo $PHP_VERSION)" >> $GITHUB_ENV
echo "NODE_VERSION=$(echo $NODE_VERSION)" >> $GITHUB_ENV
