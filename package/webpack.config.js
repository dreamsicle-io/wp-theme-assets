// @ts-check

import fs from 'fs';
import path from 'path';
import webpack from 'webpack'; /* eslint-disable-line import/no-extraneous-dependencies */
import wpConfig from '@wordpress/scripts/config/webpack.config.js'; 
import DirArchiver from 'dir-archiver';
import RemoveEmptyScriptsPlugin from 'webpack-remove-empty-scripts';
import { execSync } from 'child_process';

const themePath = process.cwd();

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
	 * @type {string}
	 */
	themePath = '';

	/**
	 * @type {string}
	 */
	composerLockPath = '';

	/**
	 * @type {string}
	 */
	composerVendorPath = '';

	/**
	 * @type {string}
	 */
	zipPath = '';

	/**
	 * @type {string}
	 */
	pkgPath = '';

	/**
	 * @type {Record<string, any>}
	 */
	pkg = {};
	
	/**
	 * Don't use leading or trailing slashes.
	 *
	 * @type {string[]}
	 */
	zipIgnore = [];

	/**
	 * @param {Record<string, any> | undefined} options
	 */
	constructor(options = {}) {
		this.pluginName = 'ThemePackageBuilderPlugin';
		this.options = { ...this.defaults, ...options };
		this.themePath = process.cwd();
		this.pkgPath = path.join(this.themePath, 'package.json');
		this.pkg = JSON.parse(fs.readFileSync(this.pkgPath).toString());
		this.composerLockPath = path.join(this.themePath, 'composer.lock');
		this.composerVendorPath = path.join(this.themePath, 'vendor');
		this.translateCommand = `composer run translate . languages/${this.pkg.name}.pot`;
		this.zipPath = path.join(this.themePath, `${this.pkg.name}.zip`);
		this.zipIgnore = [
			'.github',
			'.vscode',
			'node_modules',
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
			`${this.pkg.name}.zip`,
		];
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
  apply(compiler) {
		// On watch
    compiler.hooks.watchRun.tap(this.pluginName, () => {
			if (compiler.modifiedFiles) {
				if (compiler.modifiedFiles.has(this.pkgPath)) {
					this.buildStyleHeader(compiler);
					this.buildReadMeHeader(compiler);
					this.buildPot(compiler);
				} else if (this.isModifiedFilePHP(compiler)) {
					this.buildPot(compiler);
				}
			} else {
				this.buildStyleHeader(compiler);
				this.buildReadMeHeader(compiler);
				this.buildPot(compiler);
			}
		});
		// On build
    compiler.hooks.beforeRun.tap(this.pluginName, () => {
			this.buildStyleHeader(compiler);
			this.buildReadMeHeader(compiler);
			this.buildPot(compiler);
		});
		compiler.hooks.afterEmit.tap(this.pluginName, () => {
			if (process.env.NODE_ENV === 'production') {
				this.replaceComposerDeps(compiler);
				this.buildZip(compiler);
			}
		});
  }

	/**
	 * @param {webpack.Compiler} compiler
	 * @return {boolean} Whether php files were detected in the changed files or not.
	 */
	isModifiedFilePHP(compiler) {
		return (Array.from(compiler.modifiedFiles || []).length === 1);
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
	replaceComposerDeps(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		if (fs.existsSync(this.composerLockPath)) {
			fs.rmSync(this.composerLockPath, { force: true });
		}
		if (fs.existsSync(this.composerVendorPath)) {
			fs.rmSync(this.composerVendorPath, { recursive: true, force: true });
		}
		execSync('composer install --no-dev', { stdio: 'inherit' });
		logger.info('Replaced composer dependencies successfully');
	}

	/**
	 * @param {webpack.Compiler} compiler
	 */
	buildStyleHeader(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		const data = {
			'Theme Name': this.pkg.themeName || this.pkg.name || '',
			'Theme URI': this.pkg.homepage || '',
			'Author': this.pkg.author.name || '',
			'Author URI': this.pkg.author.url || '',
			'Description': this.pkg.description || '',
			'Version': this.pkg.version || '',
			'License': this.pkg.license || '',
			'License URI': 'LICENSE',
			'Text Domain': this.pkg.name || '',
			'Tags': Array.isArray(this.pkg.keywords) ? this.pkg.keywords.join(', ') : '',
		};
		let contents = '/*!\n';
		for (const key in data) {
			contents += `${key}: ${data[key]}\n`;
		}
		contents += '*/\n';
		fs.writeFileSync('./style.css', contents);
		logger.info('Style header built successfully');
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
	buildReadMeHeader(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		const contributorNames = this.pkg.author.name ? [this.pkg.author.name] : [];
		if (Array.isArray(this.pkg.contributors)) {
			this.pkg.contributors.forEach((contributor) => contributorNames.push(contributor.name));
		}
		const data = {
			'Contributors': contributorNames.join(', '),
			'Version': this.pkg.version,
			'Requires at least': 'WordPress ' + this.pkg.wordpress.versionRequired,
			'Tested up to': 'WordPress ' + this.pkg.wordpress.versionTested,
			'License': this.pkg.license,
			'License URI': 'LICENSE',
			'Tags': Array.isArray(this.pkg.keywords) ? this.pkg.keywords.join(', ') : '',
		};
		let content = `=== ${this.pkg.themeName || this.pkg.name || ''} ===\n\n`;
		for (const key in data) {
			content += `${key}: ${data[key]}\n`;
		}
		content += `\n${this.pkg.description}\n`;
		fs.writeFileSync('./README.txt', content);
		logger.info('README header built successfully');
	}

	/**
	 * @param {webpack.Compiler} compiler
	 */
	buildPot(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		execSync(this.translateCommand, { stdio: 'inherit' });
		logger.info('POT file built successfully');
	}

	/**
	 * @param {webpack.Compiler} compiler
	 */
	buildZip(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		// Override DirArchiver create zip function to be able to hook
		// into the output's `on('close', ...)` event.
		/* @ts-ignore */
		DirArchiver.prototype._createZip = DirArchiver.prototype.createZip;
		DirArchiver.prototype.createZip = function() {
			/* @ts-ignore */
			this._createZip();
			this.output?.on('close', function() {
				logger.info('Theme zipped successfully');
			});
		}
		const zipper = new DirArchiver(this.themePath, this.zipPath, false, this.zipIgnore);
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
			'**/package-lock.json',
			'**/phpcs.xml',
			'**/README.md',
			'**/README.txt',
			'**/style.css',
			'**/webpack.config.js',
			'**/*.zip',
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
		'js/site.min': path.resolve(themePath, 'src/js/site.ts'),
		'js/admin.min': path.resolve(themePath, 'src/js/admin.ts'),
		'js/login.min': path.resolve(themePath, 'src/js/login.ts'),
		'js/customizer-preview.min': path.resolve(themePath, 'src/js/customizer-preview.ts'),
		'js/customizer-controls.min': path.resolve(themePath, 'src/js/customizer-controls.ts'),
		// Styles.
		'css/site.min': path.resolve(themePath, 'src/scss/site.scss'),
		'css/admin.min': path.resolve(themePath, 'src/scss/admin.scss'),
		'css/login.min': path.resolve(themePath, 'src/scss/login.scss'),
		'css/editor.min': path.resolve(themePath, 'src/scss/editor.scss'),
		'css/customizer-preview.min': path.resolve(themePath, 'src/scss/customizer-preview.scss'),
		'css/customizer-controls.min': path.resolve(themePath, 'src/scss/customizer-controls.scss'),
	}
};

export default config;
