/* eslint-disable import/no-extraneous-dependencies */
import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const themeDirectory = process.cwd();
const pkgPath = path.join(themeDirectory, 'package.json');
const pkg = JSON.parse(fs.readFileSync(pkgPath).toString());

try {
	const translateCommand = `composer run translate . languages/${pkg.name}.pot`;
	execSync(translateCommand, { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
