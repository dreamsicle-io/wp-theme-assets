/* eslint-disable import/no-extraneous-dependencies */
import { execSync } from 'child_process';

// On actions and pipelines, `npm ci` is used. This checks
// which script is triggering the prepare script.
const isCI = process.env.npm_command === 'ci';

try {
	// Use only the lock file when installing through `npm ci`.
	const composerCommand = isCI ? 'composer install' : 'composer update';
	execSync(composerCommand, { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
