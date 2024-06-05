// @ts-check

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import webpack from 'webpack'; // eslint-disable-line import/no-extraneous-dependencies
import archiver from 'archiver';
import { glob } from 'glob'; // eslint-disable-line import/no-extraneous-dependencies
import wpConfig from '@wordpress/scripts/config/webpack.config.js'; 
import RemoveEmptyScriptsPlugin from 'webpack-remove-empty-scripts';

const themePath = process.cwd();

class ThemePackageBuilderPlugin {

	/**
	 * @type {string}
	 */
	pluginName = '';

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
	pkgPath = '';

	/**
	 * @type {string}
	 */
	tmpPath = '';

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
	composerLockBackupPath = '';

	/**
	 * @type {string}
	 */
	composerVendorBackupPath = '';

	/**
	 * @type {Record<string, any>}
	 */
	pkg = {};

	/**
	 * @type {string[]}
	 */
	phpIgnore = [];
	
	/**
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
		this.tmpPath = path.join(this.themePath, 'tmp');
		this.composerLockPath = path.join(this.themePath, 'composer.lock');
		this.composerVendorPath = path.join(this.themePath, 'vendor');
		this.composerLockBackupPath = path.join(this.tmpPath, 'composer.lock');
		this.composerVendorBackupPath = path.join(this.tmpPath, 'vendor');
		this.pkg = this.readPackage();
		this.phpIgnore = [
			'**/.github/**',
			'**/.vscode/**',
			'**/node_modules/**',
			'**/vendor/**',
			'**/build/**',
			'**/tmp/**',
		];
		this.zipIgnore = [
			'**/.github/**',
			'**/.vscode/**',
			'**/node_modules/**',
			'**/src/**',
			'**/tmp/**',
			'**/.editorconfig',
			'**/.eslintrc',
			'**/.gitignore',
			'**/.nvmrc',
			'**/.prettierignore',
			'**/.stylelintrc',
			'**/composer.json',
			'**/composer.lock',
			'**/package-lock.json',
			'**/package.json',
			'**/phpcs.xml',
			'**/README.md',
			'**/webpack.config.js',
			'**/*.d.ts',
			'**/tsconfig.json',
			'**/*.zip',
		];
	}

	readPackage() {
		return JSON.parse(fs.readFileSync(this.pkgPath).toString());
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
	apply(compiler) {
		compiler.hooks.afterCompile.tap(this.pluginName, (compilation) => {
			// Watch PHP files by adding them to the fileDependencies.
			const phpFiles = glob.sync('**/*.php', { 
				cwd: this.themePath,
				ignore: this.phpIgnore,
				absolute: true,
			});
			// Resolve all relative paths.
			compilation.fileDependencies.addAll(phpFiles);
		});
		// On watch.
		compiler.hooks.watchRun.tap(this.pluginName, () => {
			if (compiler.modifiedFiles) {
				// Files have changed.
				if (compiler.modifiedFiles.has(this.pkgPath)) {
					this.pkg = this.readPackage();
					this.buildStyleHeader(compiler);
					this.buildReadMeHeader(compiler);
					this.buildPot(compiler);
				} else if (this.isModifiedFilePHP(compiler)) {
					this.buildPot(compiler);
				}
			} else {
				// Initial run.
				this.buildStyleHeader(compiler);
				this.buildReadMeHeader(compiler);
				this.buildPot(compiler);
			}
		});
		// On build.
		compiler.hooks.beforeRun.tap(this.pluginName, () => {
			this.buildStyleHeader(compiler);
			this.buildReadMeHeader(compiler);
			this.buildPot(compiler);
		});
		compiler.hooks.done.tap(this.pluginName, () => {
			if (process.env.NODE_ENV === 'production') {
				this.buildZip(compiler);
			}
		});
	}

	/**
	 * @param {webpack.Compiler} compiler
	 * @return {boolean} Whether php files were detected in the changed files or not.
	 */
	isModifiedFilePHP(compiler) {
		return (Array.from(compiler.modifiedFiles || []).findIndex(file => file.endsWith('.php')) !== -1);
	}
	
	/**
	 * @param {webpack.Compiler} compiler
	 */
	replaceComposerDeps(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		if (fs.existsSync(this.composerLockPath)) {
			fs.cpSync(this.composerLockPath, this.composerLockBackupPath, { force: true });
			fs.rmSync(this.composerLockPath, { force: true });
		}
		if (fs.existsSync(this.composerVendorPath)) {
			fs.cpSync(this.composerVendorPath, this.composerVendorBackupPath, { recursive: true, force: true });
			fs.rmSync(this.composerVendorPath, { recursive: true, force: true });
		}
		execSync('composer install --no-dev', { stdio: 'inherit' });
		logger.info('Replaced composer dependencies successfully');
	}

	/**
	 * @param {webpack.Compiler} compiler
	 */
	restoreComposerDeps(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		if (fs.existsSync(this.composerLockBackupPath)) {
			fs.cpSync(this.composerLockBackupPath, this.composerLockPath, { force: true });
			fs.rmSync(this.composerLockBackupPath, { force: true });
		}
		if (fs.existsSync(this.composerVendorBackupPath)) {
			fs.cpSync(this.composerVendorBackupPath, this.composerVendorPath, { recursive: true, force: true });
			fs.rmSync(this.composerVendorBackupPath, { recursive: true, force: true });
		}
		if (fs.readdirSync(this.tmpPath).length < 1) {
			fs.rmSync(this.tmpPath, { recursive: true, force: true });
		}
		logger.info('Restored composer dependencies successfully');
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
		execSync(`composer run translate . languages/${this.pkg.name}.pot`, { stdio: 'inherit' });
		logger.info('POT file built successfully');
	}

	/**
	 * @param {webpack.Compiler} compiler
	 */
	buildZip(compiler) {
		const logger = compiler.getInfrastructureLogger(this.pluginName);
		const zipPath = path.join(this.themePath, `${this.pkg.name}.zip`);
		const output = fs.createWriteStream(zipPath);
		const archive = archiver('zip', { zlib: { level: 9 } });
		// On start.
		output.on('open', () => {
			this.replaceComposerDeps(compiler);
			logger.info('Zipping theme...');
		});
		// On completion.
		output.on('close', () => {
			logger.info('Theme zipped successfully');
			this.restoreComposerDeps(compiler);
		});
		// On output error.
		output.on('error', (error) => {
			logger.error(error);
		});
		// On archive error.
		archive.on('error', (error) => {
			logger.error(error);
		});
		// Pipe the output stream to the archive.
		archive.pipe(output);
		// Add files through glob.
		archive.glob('**', {
			cwd: this.themePath,
			ignore: this.zipIgnore,
			dot: true,
		});
		// Finalize the archive.
		archive.finalize();
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
			/**
			 * These globs must begin with `**`.
			 * @see https://webpack.js.org/configuration/watch/#watchoptionsignored
			 */
			'**/.github/**/*',
			'**/.vscode/**/*',
			'**/build/**/*',
			'**/languages/**/*',
			'**/node_modules/**/*',
			'**/vendor/**/*',
			'**/tmp/**/*',
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
		new RemoveEmptyScriptsPlugin({
			/**
			 * Process after plugins to still allow wp-scripts to output RTL assets and the asset.php file.
			 * @see https://www.npmjs.com/package/webpack-remove-empty-scripts#specify-stage-for-properly-work-some-plugins
			 */
			stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
		}),
		new ThemePackageBuilderPlugin(),
	],
	entry: {
		...((typeof wpConfig.entry === 'object') ? wpConfig.entry : {}),
		'site.min': [
			path.join(themePath, 'src/site.ts'),
			path.join(themePath, 'src/site.scss'),
		],
		'admin.min': [
			path.join(themePath, 'src/admin.ts'),
			path.join(themePath, 'src/admin.scss'),
		],
		'login.min': [
			path.join(themePath, 'src/login.ts'),
			path.join(themePath, 'src/login.scss'),
		],
		'customizer-preview.min': [
			path.join(themePath, 'src/customizer-preview.ts'),
			path.join(themePath, 'src/customizer-preview.scss'),
		],
		'customizer-controls.min': [
			path.join(themePath, 'src/customizer-controls.ts'),
			path.join(themePath, 'src/customizer-controls.scss'),
		],
		'editor.min': [
			path.join(themePath, 'src/editor.scss'),
		],
	}
};

export default config;
