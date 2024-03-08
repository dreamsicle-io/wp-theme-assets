const wpConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const path = require('path');

const themeDirectory = process.cwd();

module.exports = {
	...wpConfig,
	plugins: [...wpConfig.plugins, new RemoveEmptyScriptsPlugin()],
	entry: {
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
