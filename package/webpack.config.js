/* eslint-disable import/no-extraneous-dependencies */
const wpConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const path = require('path');
const execSync = require('child_process').execSync;

const themeDirectory = process.cwd();

const wpConfigWatchIgnore = [];
if (wpConfig.watchOptions?.ignored) {
	const ignored = Array.isArray(wpConfig.watchOptions.ignored) ? wpConfig.watchOptions.ignored : [wpConfig.watchOptions.ignored];
	wpConfigWatchIgnore.push(...ignored);
}

module.exports = {
	...wpConfig,
	watchOptions: {
		...wpConfig.watchOptions,
		ignored: [
			...wpConfigWatchIgnore,
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
		...wpConfig.plugins,
		new RemoveEmptyScriptsPlugin(),
		{
			apply: (compiler) => {
				compiler.hooks.beforeCompile.tap('BeforeCompilePlugin', () => {
					execSync('node scripts/package.mjs', { stdio: 'inherit' });
					execSync('node scripts/translate.mjs', { stdio: 'inherit' });
				});
			},
		},
	],
	entry: {
		...wpConfig.entry,
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
	},
};
