import fs from 'fs';
import path from 'path';

const themeDirectory = process.cwd();
const pkgPath = path.join(themeDirectory, 'package.json');
const pkg = JSON.parse(fs.readFileSync(pkgPath).toString());

/**
 * Build Style Header
 */
function buildStyleHeader() {
	const data = {
		'Theme Name': pkg.themeName || pkg.name || '',
		'Theme URI': pkg.homepage || '',
		'Author': pkg.author.name || '',
		'Author URI': pkg.author.url || '',
		'Description': pkg.description || '',
		'Version': pkg.version || '',
		'License': pkg.license || '',
		'License URI': 'LICENSE',
		'Text Domain': pkg.name || '',
		'Tags': Array.isArray(pkg.keywords) ? pkg.keywords.join(', ') : '',
	};
	let contents = '/*!\n';
	for (const key in data) {
		contents += `${key}: ${data[key]}\n`;
	}
	contents += '*/\n';
	fs.writeFileSync('./style.css', contents);
}

/**
 * Build README Headers
 */
function buildReadMeHeader() {
	const pkgName = pkg.themeName || pkg.name || '';
	const contributorNames = pkg.author.name ? [pkg.author.name] : [];
	if (Array.isArray(pkg.contributors)) {
		pkg.contributors.forEach((contributor) => {
			contributorNames.push(contributor.name);
		});
	}
	const data = {
		'Contributors': contributorNames.join(', '),
		'Version': pkg.version,
		'Requires at least': 'WordPress ' + pkg.wordpress.versionRequired,
		'Tested up to': 'WordPress ' + pkg.wordpress.versionTested,
		'License': pkg.license,
		'License URI': 'LICENSE',
		'Tags': Array.isArray(pkg.keywords) ? pkg.keywords.join(', ') : '',
	};
	// Build README.txt header.
	let txtContent = `=== ${pkgName} ===\n\n`;
	for (const key in data) {
		txtContent += `${key}: ${data[key]}\n`;
	}
	txtContent += `\n${pkg.description}\n`;
	fs.writeFileSync('./README.txt', txtContent);
	// Build README.md header.
	let mdContent = `# ${pkgName}\n\n`;
	for (const key in data) {
		mdContent += `**${key}:** ${data[key]}\n`;
	}
	mdContent += `\n${pkg.description}\n`;
	fs.writeFileSync('./README.md', mdContent);
}

try {
	buildStyleHeader();
	buildReadMeHeader();
	console.info('Success: Package files successfully generated.');
	process.exit();
} catch (error) {
	console.error(error);
	process.exit(1);
}
