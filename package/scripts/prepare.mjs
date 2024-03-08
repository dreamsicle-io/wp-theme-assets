import { execSync } from 'child_process';

// On actions and pipelines, `npm ci` is used. Checking
// which script is triggering the prepare script allows
// checking which command is triggering this prepare script.
const isCI = process.env.npm_command === 'ci';

try {
	// Ignore dev composer deps when running `npm ci`.
	const composerCommand = isCI ? 'composer install' : 'composer update';
	execSync(composerCommand, { stdio: 'inherit' });
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
