# WP Theme 

Just another WordPress site.

## Getting Started

### Set the node version

```shell
nvm use
```

### Run a development build and watch for changes

```shell
npm start
```

## Development Commands

The theme's build and lint system is powered by Webpack, wp-scripts, and wp-cli.

### Set Node Version

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

Run a development build and watch for changes. This command will run `npm install` on the `prestart` hook.

```shell
npm start
```

### Build

Run a production build.

```shell
npm run build
```

### Lint

Lint all files with phpcs, eslint, and stylelint.

```shell
npm run lint
```

### fix

Fix all fixable issues with phpcbf, eslint, and stylelint.

```shell
npm run fix
```

## Build File Structure 

This Webpack setup expects that the project root's `package.json`, and `composer.json` is setup properly, and that an opinionated file structure is followed.

### Source Files 

``` 
root 
―――― /src 
―――― ―――― /js 
―――― ―――― ―――― /modules 
―――― ―――― ―――― site.js 
―――― ―――― ―――― admin.js 
―――― ―――― ―――― login.js 
―――― ―――― ―――― customizer-preview.js 
―――― ―――― ―――― customizer-controls.js 
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
