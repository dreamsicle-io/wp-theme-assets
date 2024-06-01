# WP Theme 

This theme was scaffolded using the [Create WP Theme](https://github.com/dreamsicle-io/create-wp-theme) CLI and the [WP Theme Assets](https://github.com/dreamsicle-io/wp-theme-assets) package.

## Getting Started

### Set the node version

```shell
nvm use
```

### Install dependencies

```shell
npm install
```

### Run a development build and watch for changes

```shell
npm start
```

## Development commands

The theme's build system is powered by [Webpack](https://webpack.js.org/), [wp-scripts](https://www.npmjs.com/package/@wordpress/scripts), and [wp-cli](https://wp-cli.org/), while linting and fixing is powered by [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer), [ESLint](https://eslint.org/), and [StyleLint](https://stylelint.io/)

### Set Node version

Uses `nvm` to set the node version as defined in the `.nvmrc` file.

```shell
nvm use
```

### Install

Install all `npm` and `composer` dependencies.

```shell
npm install
```

### Start

Run a development build and watch for changes. This command will watch for changes in `package.json` to generate required `style.css` and `README.txt` headers, as well as generate `*.pot` files.

```shell
npm start
```

### Build

Run a production build. This command will zip the theme for production, while installing production Composer dependencies and caching development composer dependencies to restore them once zipping the theme has finished.

```shell
npm run build
```

### Lint

Lint all files with `phpcs`, `eslint`, and `stylelint`.

```shell
npm run lint
```

### Fix

Fix all fixable issues with `phpcbf`, `eslint`, and `stylelint`.

```shell
npm run fix
```

### Clean dependencies

Clean all Composer and npm dependencies, including lock files. This is useful for debugging dependency issues, allowing for all dependencies to be installed fresh and new lock files to be generated.

```shell
npm run clean-deps
```

### Clean build

Clean all built files, including the `/build` directory, the translation `*.pot` file in the `/languages` directory, the `README.txt` file, and the `style.css` file. This command will also clear the `/tmp` file that is used for build utilities, should it be left around for whatever reason.

```shell
npm run clean-build
```

### Clean

Clean all dependencies, and build files.

```shell
npm run clean
```

## File Structure 

This Webpack setup expects that the project root's `package.json`, and `composer.json` files are set up properly, and that an opinionated file structure is followed.

### Source Files 

``` 
root 
―――― /src 
―――― ―――― /js 
―――― ―――― ―――― /modules 
―――― ―――― ―――― site.ts 
―――― ―――― ―――― admin.ts 
―――― ―――― ―――― login.ts 
―――― ―――― ―――― customizer-preview.ts 
―――― ―――― ―――― customizer-controls.ts 
―――― ―――― /scss 
―――― ―――― ―――― /modules 
―――― ―――― ―――― site.scss 
―――― ―――― ―――― admin.scss 
―――― ―――― ―――― login.scss 
―――― ―――― ―――― editor.scss 
―――― ―――― ―――― customizer-preview.scss 
―――― ―――― ―――― customizer-controls.scss 
``` 

### Build Files 

``` 
root 
―――― /build 
―――― ―――― /js 
―――― ―――― ―――― site.min.js 
―――― ―――― ―――― site.min.js.map 
―――― ―――― ―――― admin.min.js 
―――― ―――― ―――― admin.min.js.map 
―――― ―――― ―――― login.min.js 
―――― ―――― ―――― login.min.js.map 
―――― ―――― ―――― customizer-preview.min.js 
―――― ―――― ―――― customizer-preview.min.js.map 
―――― ―――― ―――― customizer-controls.min.js 
―――― ―――― ―――― customizer-controls.min.js.map 
―――― ―――― /css 
―――― ―――― ―――― site.min.css 
―――― ―――― ―――― site.min-rtl.css 
―――― ―――― ―――― site.min.css.map 
―――― ―――― ―――― admin.min.css 
―――― ―――― ―――― admin.min-rtl.css 
―――― ―――― ―――― admin.min.css.map 
―――― ―――― ―――― login.min.css 
―――― ―――― ―――― login.min-rtl.css 
―――― ―――― ―――― login.min.css.map 
―――― ―――― ―――― editor.min.css 
―――― ―――― ―――― editor.min-rtl.css 
―――― ―――― ―――― editor.min.css.map 
―――― ―――― ―――― customizer-preview.min.css 
―――― ―――― ―――― customizer-preview.min-rtl.css 
―――― ―――― ―――― customizer-preview.min.css.map 
―――― ―――― ―――― customizer-controls.min.css 
―――― ―――― ―――― customizer-controls.min-rtl.css 
―――― ―――― ―――― customizer-controls.min.css.map 
―――― /languages 
―――― ―――― {textdomain}.pot 
―――― README.txt 
―――― style.css 
```
