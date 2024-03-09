// @ts-check

import fs from 'fs';
import path from 'path';
import webpack from 'webpack'; /* eslint-disable-line import/no-extraneous-dependencies */
import wpConfig from '@wordpress/scripts/config/webpack.config.js';
import RemoveEmptyScriptsPlugin from 'webpack-remove-empty-scripts';
import DirArchiver from 'dir-archiver';
import { execSync } from 'child_process';

const themeDirectory = process.cwd();
const pkgPath = path.join(themeDirectory, 'package.json');
const pkg = JSON.parse(fs.readFileSync(pkgPath).toString());

class ThemePackageBuilderPlugin {

	/**
	 * @type {Record<string, any>}
	 */
	options = {};

	/**
	 * @type {Record<string, any>}
	 */
	defaults = {};

	/**
	 * @param {Record<string, any> | undefined} options
	 */
	constructor(options = {}) {
		this.options = { ...this.defaults, ...options };
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
  apply(compiler) {
    compiler.hooks.beforeRun.tap('ThemePackageBuilderPlugin', () => {
			this.buildStyleHeader();
			this.buildReadMeHeaders();
			this.buildPot();
		});
		compiler.hooks.done.tap('ThemePackageBuilderPlugin', () => {
			this.buildZip();
		});
  }

	buildStyleHeader() {
		const data = {
			'Theme Name': pkg.themeName || pkg.name || '',
			'Theme URI': pkg.homepage || '',
			'Author': pkg.author.name || '',
			'Author URI': pkg.author.url || '',
			'Description': pkg.description || '',
			'Version': pkg.version || '',
			'License': pkg.license || '',
			'License URI': 'LICENSE',
			'Text Domain': pkg.name || '',
			'Tags': Array.isArray(pkg.keywords) ? pkg.keywords.join(', ') : '',
		};
		let contents = '/*!\n';
		for (const key in data) {
			contents += `${key}: ${data[key]}\n`;
		}
		contents += '*/\n';
		fs.writeFileSync('./style.css', contents);
	}
	
	buildReadMeHeaders() {
		const pkgName = pkg.themeName || pkg.name || '';
		const contributorNames = pkg.author.name ? [pkg.author.name] : [];
		if (Array.isArray(pkg.contributors)) {
			pkg.contributors.forEach((contributor) => {
				contributorNames.push(contributor.name);
			});
		}
		const data = {
			'Contributors': contributorNames.join(', '),
			'Version': pkg.version,
			'Requires at least': 'WordPress ' + pkg.wordpress.versionRequired,
			'Tested up to': 'WordPress ' + pkg.wordpress.versionTested,
			'License': pkg.license,
			'License URI': 'LICENSE',
			'Tags': Array.isArray(pkg.keywords) ? pkg.keywords.join(', ') : '',
		};
		// Build README.txt header.
		let txtContent = `=== ${pkgName} ===\n\n`;
		for (const key in data) {
			txtContent += `${key}: ${data[key]}\n`;
		}
		txtContent += `\n${pkg.description}\n`;
		fs.writeFileSync('./README.txt', txtContent);
		// Build README.md header.
		let mdContent = `# ${pkgName}\n\n`;
		for (const key in data) {
			mdContent += `**${key}:** ${data[key]}\n`;
		}
		mdContent += `\n${pkg.description}\n`;
		fs.writeFileSync('./README.md', mdContent);
	}

	buildPot() {
		execSync(`composer run translate . languages/${pkg.name}.pot`, { stdio: 'inherit' });
	}

	buildZip() {
		// Don't use leading or slashes.
		const ignores = [
			'.github',
			'.vscode',
			'node_modules',
			'vendor',
			'scripts',
			'src',
			'.editorconfig',
			'.eslintrc',
			'.gitignore',
			'.nvmrc',
			'.prettierignore',
			'.stylelintrc',
			'composer.json',
			'composer.lock',
			'package-lock.json',
			'package.json',
			'phpcs.xml',
			'README.md',
			'webpack.config.js',
			`${pkg.name}.zip`,
		];
		const zipper = new DirArchiver(themeDirectory, path.join(themeDirectory, `${pkg.name}.zip`), false, ignores);
		zipper.createZip();
	}
}

/**
 * @type {webpack.Configuration}
 */
const config = {
	...wpConfig,
	watchOptions: {
		...wpConfig.watchOptions,
		ignored: [
			...(Array.isArray(wpConfig.watchOptions?.ignored) ? wpConfig.watchOptions.ignored : []),
			// These globs must begin with '**/'.
			// See: https://webpack.js.org/configuration/watch/#watchoptionsignored
			'**/.github/**/*',
			'**/.vscode/**/*',
			'**/build/**/*',
			'**/languages/**/*',
			'**/node_modules/**/*',
			'**/scripts/**/*',
			'**/vendor/**/*',
			'**/.editorconfig',
			'**/.eslintrc',
			'**/.gitignore',
			'**/.nvmrc',
			'**/.prettierignore',
			'**/.stylelintrc',
			'**/composer.json',
			'**/composer.lock',
			'**/LICENSE',
			'**/package.json',
			'**/package-lock.json',
			'**/phpcs.xml',
			'**/README.md',
			'**/README.txt',
			'**/style.css',
			'**/webpack.config.js',
		],
	},
	plugins: [
		...(Array.isArray(wpConfig.plugins) ? wpConfig.plugins : []),
		new RemoveEmptyScriptsPlugin(),
		new ThemePackageBuilderPlugin(),
	],
	entry: {
		...((typeof wpConfig.entry === 'object') ? wpConfig.entry : {}),
		// Scripts.
		'js/site.min': path.resolve(themeDirectory, 'src/js/site.ts'),
		'js/admin.min': path.resolve(themeDirectory, 'src/js/admin.ts'),
		'js/login.min': path.resolve(themeDirectory, 'src/js/login.ts'),
		'js/customizer-preview.min': path.resolve(themeDirectory, 'src/js/customizer-preview.ts'),
		'js/customizer-controls.min': path.resolve(themeDirectory, 'src/js/customizer-controls.ts'),
		// Styles.
		'css/site.min': path.resolve(themeDirectory, 'src/scss/site.scss'),
		'css/admin.min': path.resolve(themeDirectory, 'src/scss/admin.scss'),
		'css/login.min': path.resolve(themeDirectory, 'src/scss/login.scss'),
		'css/editor.min': path.resolve(themeDirectory, 'src/scss/editor.scss'),
		'css/customizer-preview.min': path.resolve(themeDirectory, 'src/scss/customizer-preview.scss'),
		'css/customizer-controls.min': path.resolve(themeDirectory, 'src/scss/customizer-controls.scss'),
	}
};

export default config;
