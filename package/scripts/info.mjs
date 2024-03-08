/* eslint-disable import/no-extraneous-dependencies */
import { execSync } from 'child_process';

try {
	// PHP.
	console.info('\n\u{1F9E1} PHP info\n');
	execSync('php -v', { stdio: 'inherit' });
	// Composer.
	console.info('\n\u{1F9E1} Composer info\n');
	execSync('composer -V', { stdio: 'inherit' });
	// Node.
	console.info('\n\u{1F9E1} Node info\n');
	execSync('node -v', { stdio: 'inherit' });
	// NPM
	console.info('\n\u{1F9E1} NPM info\n');
	execSync('npm -v', { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
