import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const themeDirectory = process.cwd();
const pkgPath = path.join(themeDirectory, 'package.json');
const pkg = JSON.parse(fs.readFileSync(pkgPath).toString());

try {
	const ignores = [
		'.github/',
		'.vscode/',
		'node_modules/',
		'vendor/',
		'scripts/',
		'src/',
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
	];
	const zipCommand = `zip -r ${pkg.name}.zip . -X ${ignores.map(ignore => `"${ignore}"`).join(' ')}`;
	execSync(zipCommand, { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
