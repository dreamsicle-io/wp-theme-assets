# WP Theme Assets 

A simple, zero configuration [Gulp](https://gulpjs.com) build &amp; lint setup for developing modern [WordPress](https://wordpress.org) themes.

This package contains a task runner built entirely on Gulp 4, and will lint `.php` according to the WordPress coding standards, build and optimize `.js`, `.scss` assets, generate a `.pot` language localization file, and optimize images. This package will also create the required `style.css` file and its formatted header comment, as well as the required, [theme directory](https://wordpress.org/themes) friendly `README.md` file with all it's content. This package also comes bundled with utilities to automatically fix code issues, GitHub actions for automatically building release assets and creating releases, and contains recommended extensions and settings for the [VSCode](https://code.visualstudio.com/) IDE.

Finally, this package also includes a `WP_Theme_Assets` [php class](https://github.com/dreamsicle-io/wp-theme-assets/blob/master/package/includes/class-wp-theme-assets.php), located in `includes/class-wp-theme-assets.php`. This class beautifully handles all script and stylesheet enqueues of built assets automatically. 

## Getting Started

The best way to scaffold this project is to use [@dreamsicle.io/create-wp-theme](https://github.com/dreamsicle-io/create-wp-theme). This is a node command line utility that will walk you through a prompt in the command line.

### 1. Scaffold a Theme

**From the WordPress install's `wp-content/themes` directory, run `create-wp-theme`:**

```shell
npx @dreamsicle.io/create-wp-theme my-theme
```

### 2. Install Dependencies

**Navigate to the newly created theme directory:**

```shell
cd path/to/theme
```

**Run the install command:**

```shell
npm install
```

### 3. Run Builds

**Run a production build:**

```shell
npm run build
```

**Run a development build and watch for changes:**

```shell
npm start
```

## Development Commands

The included [gulpfile](https://github.com/dreamsicle-io/wp-theme-assets/blob/master/package/gulpfile.js) contains all tasks that handle linting and building this package.

### Install

Install all `npm` and `composer` dependencies.

```shell
npm install
```

### Start

Run a development build and watch for changes.

```shell
npm start
```

### Build

Run a production build.

```shell
npm run build
```

### Lint

Find and report code errors and issues.

```shell
npm run lint
```

### Fix

Correct all fixable code issues.

```shell
npm run fix
```

### Clean

Clean all built files.

```shell
npm run clean
```

## File structure 

This gulp setup expects that the project root's `package.json` is setup properly, and that an [opinionated file structure](https://github.com/dreamsicle-io/wp-theme-assets/tree/master/package) is followed. The localization tasks are handled by the built in WordPress localization functions in all theme `.php` files, but the [source assets](https://github.com/dreamsicle-io/wp-theme-assets/tree/master/package/assets) should be structured accordingly.

### Source Files 

``` 
root 
―――― /assets 
―――― ―――― /src 
―――― ―――― ―――― /js 
―――― ―――― ―――― ―――― /modules 
―――― ―――― ―――― ―――― site.js 
―――― ―――― ―――― ―――― admin.js 
―――― ―――― ―――― ―――― login.js 
―――― ―――― ―――― ―――― customizer-preview.js 
―――― ―――― ―――― ―――― customizer-controls.js 
―――― ―――― ―――― /md 
―――― ―――― ―――― ―――― DESCRIPTION.js 
―――― ―――― ―――― ―――― FAQ.js 
―――― ―――― ―――― ―――― COPYRIGHT.js 
―――― ―――― ―――― ―――― CHANGELOG.js 
―――― ―――― ―――― /sass 
―――― ―――― ―――― ―――― /modules 
―――― ―――― ―――― ―――― site.scss 
―――― ―――― ―――― ―――― admin.scss 
―――― ―――― ―――― ―――― login.scss 
―――― ―――― ―――― ―――― editor.scss 
―――― ―――― ―――― ―――― customizer-preview.scss 
―――― ―――― ―――― ―――― customizer-controls.scss 
―――― ―――― ―――― /images 
``` 

### Build Files 

``` 
root 
―――― /assets 
―――― ―――― /dist 
―――― ―――― ―――― /js 
―――― ―――― ―――― ―――― site.min.js 
―――― ―――― ―――― ―――― site.min.js.map 
―――― ―――― ―――― ―――― admin.min.js 
―――― ―――― ―――― ―――― admin.min.js.map 
―――― ―――― ―――― ―――― login.min.js 
―――― ―――― ―――― ―――― login.min.js.map 
―――― ―――― ―――― ―――― customizer-preview.min.js 
―――― ―――― ―――― ―――― customizer-preview.min.js.map 
―――― ―――― ―――― ―――― customizer-controls.min.js 
―――― ―――― ―――― ―――― customizer-controls.min.js.map 
―――― ―――― ―――― /css 
―――― ―――― ―――― ―――― site.min.css 
―――― ―――― ―――― ―――― site.min.css.map 
―――― ―――― ―――― ―――― admin.min.css 
―――― ―――― ―――― ―――― admin.min.css.map 
―――― ―――― ―――― ―――― login.min.css 
―――― ―――― ―――― ―――― login.min.css.map 
―――― ―――― ―――― ―――― editor.min.css 
―――― ―――― ―――― ―――― editor.min.css.map 
―――― ―――― ―――― ―――― customizer-preview.min.css 
―――― ―――― ―――― ―――― customizer-preview.min.css.map 
―――― ―――― ―――― ―――― customizer-controls.min.css 
―――― ―――― ―――― ―――― customizer-controls.min.css.map 
―――― ―――― ―――― /images 
―――― /languages 
―――― ―――― {textdomain}.pot 
―――― README.md 
―――― style.css 
```
