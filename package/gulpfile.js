'use-strict';

import babel from 'babelify';
import browserify from 'browserify';
import del from 'del';
import fs from 'fs';
import gulp from 'gulp';
import autoPrefixer from 'gulp-autoprefixer';
import cached from 'gulp-cached';
import concat from 'gulp-concat';
import debug from 'gulp-debug';
import gulpIf from 'gulp-if';
import imagemin from 'gulp-imagemin';
import order from 'gulp-order';
import rename from 'gulp-rename';
import gulpSass from 'gulp-sass';
import sassCompiler from 'sass';
import sourcemaps from 'gulp-sourcemaps';
import tap from 'gulp-tap';
import GulpUglify from 'gulp-uglify';
import gulpWPpot from 'gulp-wp-pot';
import buffer from 'vinyl-buffer';
import GulpZip from 'gulp-zip';
const sass = gulpSass(sassCompiler);

/**
 * Clean package files.
 *
 * Process:
 *	 1. Deletes the default style.css file containing generated theme header.
 *	 2. Deletes the README.md file containing generated documentation.
 */
gulp.task('clean', function packageCleaner(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json').toString());
	del([
		'./README.md',
		'./style.css',
		'./assets/dist',
		'./languages/*.pot',
		'./' + pkg.name + '.zip'
	]).then(function () {
		return done();
	}).catch(function (err) {
		console.error(err);
		return done();
	});
});

/**
 * Build SASS.
 *
 * Process:
 *	 1. Imports all SASS modules to file.
 *	 2. Compiles the SASS to CSS.
 *	 3. Minifies the file if the environment is production.
 *	 4. Adds all necessary vendor prefixes to CSS.
 *	 5. Renames the compiled file to *.min.css.
 *	 6. Writes sourcemaps to initial content.
 *	 7. Writes created files to the build directory.
 *	 8. Logs created files to the console.
 */
gulp.task('build:sass', function sassBuilder() {
	const outputStyle = (process.env.NODE_ENV === 'production') ? 'compressed' : 'expanded';
	return gulp.src(['./assets/src/sass/*.s+(a|c)ss'])
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(sass({ includePaths: ['node_modules'], outputStyle: outputStyle, cascade: false })
			.on('error', function (err) { console.error(err); this.emit('end'); }))
		.pipe(cached('build:sass'))
		.pipe(autoPrefixer())
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/css'))
		.pipe(debug({ title: 'build:sass' }));
});

/**
 * Build All JS.
 *
 * Process:
 *	 1. Imports JS modules to file. 
 *	 2. Transpiles the file with Babel including ESM.
 *	 3. Minifies the file.
 *	 4. Renames the compiled file to *.min.js.
 *	 5. Writes sourcemaps to initial content.
 *	 6. Writes created files to the build directory.
 *	 7. Logs created files to the console.
 */
gulp.task('build:js', function jsBuilder() {
	return gulp.src('./assets/src/js/*.js', { read: false }) // browserify reads file, don't read file twice.
		.pipe(tap(function (file) {
			const bundler = browserify(file.path, { debug: true, plugin: [ [esmify, {}] ] }).transform(babel, { presets: ['@babel/preset-env'] });
			file.contents = bundler.bundle();
		}).on('error', function (err) { console.error(err); this.emit('end'); }))
		.pipe(buffer())
		.pipe(cached('build:js'))
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(gulpIf((process.env.NODE_ENV === 'production'), GulpUglify()))
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/js'))
		.pipe(debug({ title: 'build:js' }));
});

/**
 * Build localization files for translations.
 *
 * Process:
 *	 1. Reads php files for WordPress localization functions. 
 *	 2. Processes values related to this package's text domain. 
 *	 3. Writes created .pot file to the localization directory.
 *	 4. Logs created files to the console.
 */
gulp.task('build:pot', function potBuilder() {
	const pkg = JSON.parse(fs.readFileSync('./package.json').toString());
	return gulp.src(['./**/*.php', '!./+(vendor|node_modules|assets|languages)/**'])
		.pipe(gulpWPpot({ domain: pkg.name })
			.on('error', function (err) { console.error(err); this.emit('end'); }))
		.pipe(cached('build:pot'))
		.pipe(gulp.dest('./languages/' + pkg.name + '.pot'))
		.pipe(debug({ title: 'build:pot' }));
});

/**
 * Build Images.
 *
 * Process:
 *	 1. Process and optimize all images. 
 *	 2. Writes optimized images to the build directory.
 *	 3. Logs created files to the console.
 */
gulp.task('build:images', function imageBuilder() {
	return gulp.src(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'])
		.pipe(cached('build:images'))
		.pipe(imagemin([
			svgo({ plugins: [{ name: 'cleanupIDs', active: false }] })
		])
			.on('error', function (err) { console.error(err); this.emit('end'); }))
		.pipe(gulp.dest('./assets/dist/images'))
		.pipe(debug({ title: 'build:images' }));
});

/**
 * Build Package Stylesheet Header.
 *
 * Process:
 *	 1. Prepares the necessary data from the package.json file. 
 *	 2. Writes the required style.css file with formatted header comment.
 *
 * Expected output: 
 *   Theme Name:  ...
 *   Theme URI:   ...
 *   Author:      ...
 *   Author URI:  ...
 *   Description: ...
 *   Version:     ...
 *   License:     ...
 *   License URI: ...
 *   Text Domain: ...
 *   Tags:        ...
 */
gulp.task('build:package:style', function packageStyleBuilder(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json').toString());
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
		'Tags': (pkg.keywords.length > 0) ? pkg.keywords.join(', ') : '',
	};
	var contents = '/*!\n';
	for (var key in data) {
		contents += key + ': ' + data[key] + '\n';
	}
	contents += '*/\n';
	fs.writeFileSync('./style.css', contents);
	return done();
});

/**
 * Build Package README Header.
 *
 * Process:
 *	 1. Prepares the necessary data from the package.json file. 
 *	 2. Writes the `README.md` file formatted for MNRK Repo. 
 *
 * Expected output: 
 *   Contributors:      ...
 *   Version:           ...
 *   Requires at least: ...
 *   Tested up to:      ...
 *   License:           ...
 *   License URI:       ...
 *   Tags:              ...
 */
gulp.task('build:package:readme:header', function packageReadmeHeaderBuilder(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json').toString());
	const pkgName = pkg.themeName || pkg.name || '';
	var contributorNames = pkg.author.name ? [pkg.author.name] : [];
	if (pkg.contributors && pkg.contributors.length > 0) {
		pkg.contributors.forEach((contributor) => {
			contributorNames.push(contributor.name);
		});
	}
	const data = {
		'Contributors': (contributorNames.length > 0) ? contributorNames.join(', ') : '',
		'Version': pkg.version,
		'Requires at least': 'WordPress ' + pkg.wordpress.versionRequired,
		'Tested up to': 'WordPress ' + pkg.wordpress.versionTested,
		'License': pkg.license,
		'License URI': 'LICENSE',
		'Tags': (pkg.keywords.length > 0) ? pkg.keywords.join(', ') : '',
	};
	var contents = '# ' + pkgName + '\n\n';
	for (var key in data) {
		contents += '**' + key + ':** ' + data[key] + '\n';
	}
	contents += '\n' + pkg.description + '\n';
	fs.writeFileSync('./README.md', contents);
	return done();
});

/**
 * Build Package README Content.
 *
 * Process:
 *	 1. Concatenates the README.md file with source md files in the right order. 
 *	 2. Writes the concatenated files to the README.md file formatted for MNRK Repo.
 *	 3. Logs created files to the console.
 *
 * Expected output: 
 *   Header (see `build:package:readme:header` task)
 *   Description
 *   Frequently Asked Questions
 *   Copyright
 *   Change Log
 */
gulp.task('build:package:readme:content', function packageReadmeContentBuilder() {
	return gulp.src(['./README.md', './assets/src/md/+(DESCRIPTION|FAQ|COPYRIGHT|CHANGELOG).md'])
		.pipe(order(['README.md', 'DESCRIPTION.md', 'FAQ.md', 'COPYRIGHT.md', 'CHANGELOG.md']))
		.pipe(concat('README.md'))
		.pipe(cached('build:package:readme:content'))
		.pipe(gulp.dest('./'))
		.pipe(debug({ title: 'build:package:readme:content' }));
});

/**
 * Build Package README.
 *
 * Process:
 *	 1. Runs the `build:package:readme:header` task. 
 *	 2. Runs the `build:package:readme:content` task. 
 */
gulp.task('build:package:readme', gulp.series('build:package:readme:header', 'build:package:readme:content'));

/**
 * Build Package.
 *
 * Process:
 *	 1. Runs the `build:package:style` task. 
 *	 2. Runs the `build:package:readme` task. 
 */
gulp.task('build:package', gulp.series('build:package:style', 'build:package:readme'));

/**
 * Build all assets.
 *
 * Process:
 *	 1. Runs the `build:package` task.
 *	 2. Runs the `build:pot` task.
 *	 3. Runs the `build:sass` task.
 *	 4. Runs the `build:js` task.
 *	 5. Runs the `build:images` task.
 */
gulp.task('build:assets', gulp.series('build:package', 'build:pot', 'build:sass', 'build:js', 'build:images'));

/**
 * Zip.
 *
 * Process:
 *	 1. Zips a WordPress friendly theme.
 */
gulp.task('zip', function zipper() {
	const pkg = JSON.parse(fs.readFileSync('./package.json').toString());
	const zipName = pkg.name + '.zip';
	const zipSrc = [
		'./**',
		'!./assets/src/**',
		'!./**/.gitkeep',
		'!./composer.json',
		'!./composer.lock',
		'!./package.json',
		'!./package-lock.json',
		'!./gulpfile.js',
		'!./prepare.js',
		'!./phpcs.xml',
		'!./.gitignore',
		'!./.eslintrc',
		'!./.stylelint',
		'!./.editorconfig',
		'!./.nvmrc',
		'!./.vscode/**',
		'!./.github/**',
		'!./node_modules/**',
		'!./' + zipName,
	];
	return gulp.src(zipSrc)
		.pipe(GulpZip(zipName))
		.pipe(gulp.dest('./'))
		.pipe(debug({ title: 'zip' }));
});

/**
 * Build.
 *
 * Process:
 *	 1. Runs the `clean` task.
 *	 2. Runs the `build:assets` task.
 *	 3. Runs the `zip` task.
 */
gulp.task('build', gulp.series('clean', 'build:assets', 'zip'));

/**
 * Watch source files and build on change.
 *
 * Process:
 *	 1. Runs the `build:package` task when the package.json file, or source md changes.
 *	 2. Runs the `build:pot` task when the source php changes.
 *	 3. Runs the `build:js` tasks when the source JS changes.
 *	 4. Runs the `build:images` task when the source images change.
 */
gulp.task('watch', function watcher() {
	gulp.watch(['./package.json', './assets/src/md/+(DESCRIPTION|FAQ|COPYRIGHT|CHANGELOG).md'], gulp.series('build:package'));
	gulp.watch(['./**/*.php', '!./+(.vscode|vendor|node_modules|assets|languages)/**'], gulp.series('build:pot'));
	gulp.watch(['./assets/src/sass/**/*.s+(a|c)ss'], gulp.series('build:sass'));
	gulp.watch(['./assets/src/js/**/*.js'], gulp.series('build:js'));
	gulp.watch(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'], gulp.series('build:images'));
});

/**
 * Build all assets (default task). 
 *
 * Process:
 *	 1. Runs the `build:assets` task.
 *	 2. Runs the `watch` task.
 */
gulp.task('default', gulp.series('build:assets', 'watch'));
