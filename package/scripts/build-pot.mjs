import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const themeDirectory = process.cwd();
const pkgPath = path.join(themeDirectory, 'package.json');
const pkg = JSON.parse(fs.readFileSync(pkgPath).toString());

try {
	const potCommand = `composer run build:pot . languages/${pkg.name}.pot`;
	execSync(potCommand, { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
