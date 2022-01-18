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
import eslint from 'gulp-eslint';
import gulpIf from 'gulp-if';
import imagemin from 'gulp-imagemin';
import order from 'gulp-order'
import rename from 'gulp-rename';
import gulpSass from 'gulp-sass';
import sassLint from 'gulp-sass-lint';
import sassCompiler from 'sass';
import sourcemaps from 'gulp-sourcemaps';
import tap from 'gulp-tap';
import GulpUglify from 'gulp-uglify';
import gulpWPpot from 'gulp-wp-pot';
import buffer from 'vinyl-buffer';
import GulpZip from 'gulp-zip';
const sass = gulpSass(sassCompiler);

// Vendor Files
const vendorCss = [];
const vendorJs = [];
const vendorImages = [];

/**
 * Clean package Files.
 *
 * Process:
 *	 1. Deletes the default style.css file containing generated theme header.
 *	 2. Deletes the README.md file containing generated documentation.
 *
 * Run:
 *	 - Global command: `gulp clean:package`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:package`.
 */
gulp.task('clean:package', function packageCleaner(done) {
	del(['./README.md', './style.css'])
		.then(function(paths) {
			return done();
		}).catch(function(err) {
			console.error(err);
			return done();
		});
});

/**
 * Clean build CSS.
 *
 * Process:
 *	 1. Deletes the CSS build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:css`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:css`.
 */
gulp.task('clean:css', function cssCleaner(done) {
	del(['./assets/dist/css'])
		.then(function(paths) {
			return done();
		}).catch(function(err) {
			console.error(err);
			return done();
		});
});

/**
 * Clean build JS.
 *
 * Process:
 *	 1. Deletes the JS build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:js`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:js`.
 */
gulp.task('clean:js', function jsCleaner(done) {
	del(['./assets/dist/js'])
		.then(function(paths) {
			return done();
		}).catch(function(err) {
			console.error(err);
			return done();
		});
});

/**
 * Clean localization build files.
 *
 * Process:
 *	 1. Deletes the localization build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:pot`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:pot`.
 */
gulp.task('clean:pot', function potCleaner(done) {
	del(['./languages/*.pot'])
		.then(function(paths) {
			return done();
		}).catch(function(err) {
			console.error(err);
			return done();
		});
});

/**
 * Clean build images.
 *
 * Process:
 *	 1. Deletes the images build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:images`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:images`.
 */
gulp.task('clean:images', function imagesCleaner(done) {
	del(['./assets/dist/images'])
		.then(function(paths) {
			return done();
		}).catch(function(err) {
			console.error(err);
			return done();
		});
});

/**
 * Clean All Built Assets.
 *
 * Process: 
 *	 1. Runs the `clean:js` task.
 *	 2. Runs the `clean:css` task.
 *	 3. Runs the `clean:images` task.
 *	 4. Runs the `clean:pot` task.
 *	 5. Runs the `clean:package` task.
 *
 * Run:
 *	 - Global command: `gulp clean`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean`.
 *	 - NPM script: `npm run clean`.
 */
gulp.task('clean', gulp.series('clean:js', 'clean:css', 'clean:images', 'clean:pot', 'clean:package'));

/**
 * Build CSS Vendor.
 *
 * Process:
 *	 1. Moves all vendor CSS to `assets/dist/css` directory.
 *
 * Run:
 *	 - Global command: `gulp build:css:vendor`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:css:vendor`.
 */
gulp.task('build:css:vendor', function VendorCssBuilder(done) {
	if (vendorCss && (vendorCss.length > 0)) {
		return gulp.src(vendorCss)
			.pipe(cached('build:css:vendor'))
			.pipe(gulp.dest('./assets/dist/css'))
			.pipe(debug({ title: 'build:css:vendor' }));
	} else {
		return done();
	}
});

/**
 * Build JS Vendor.
 *
 * Process:
 *	 1. Moves all vendor JS to `assets/dist/js` directory.
 *
 * Run:
 *	 - Global command: `gulp build:js:vendor`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js:vendor`.
 */
gulp.task('build:js:vendor', function VendorJsBuilder(done) {
	if (vendorJs && (vendorJs.length > 0)) {
		return gulp.src(vendorJs)
			.pipe(cached('build:js:vendor'))
			.pipe(gulp.dest('./assets/dist/js'))
			.pipe(debug({ title: 'build:js:vendor' }));
	} else {
		return done();
	}
});

/**
 * Build Vendor Images.
 *
 * Process:
 *	 1. Moves all vendor images to `assets/dist/images` directory.
 *
 * Run:
 *	 - Global command: `gulp build:images:vendor`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:images:vendor`.
 */
gulp.task('build:images:vendor', function VendorImagesBuilder(done) {
	if (vendorImages && (vendorImages.length > 0)) {
		return gulp.src(vendorImages)
			.pipe(cached('build:images:vendor'))
			.pipe(gulp.dest('./assets/dist/images'))
			.pipe(debug({ title: 'build:images:vendor' }));
	} else {
		return done();
	}
});

/**
 * Build Vendor.
 *
 * Process:
 *	 1. Runs the `build:css:vendor` task. 
 *	 2. Runs the `build:js:vendor` task. 
 *	 3. Runs the `build:images:vendor` task. 
 *
 * Run:
 *	 - Global command: `gulp build:vendor`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:vendor`.
 */
gulp.task('build:vendor', gulp.series('build:css:vendor', 'build:js:vendor', 'build:images:vendor'));

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
 *
 * Run:
 *	 - Global command: `gulp build:sass`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:sass`.
 */
gulp.task('build:sass', function sassBuilder() {
	const outputStyle = (process.env.NODE_ENV === 'production') ? 'compressed' : 'expanded';
	return gulp.src(['./assets/src/sass/*.s+(a|c)ss'])
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(sass({ includePaths: ['node_modules'], outputStyle: outputStyle, cascade: false })
			.on('error', function(err) { console.error(err); this.emit('end'); }))
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
 *	 2. Transpiles the file with Babel.
 *	 3. Minifies the file.
 *	 4. Renames the compiled file to *.min.js.
 *	 5. Writes sourcemaps to initial content.
 *	 6. Writes created files to the build directory.
 *	 7. Logs created files to the console.
 *
 * Run:
 *	 - Global command: `gulp build:js`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js`.
 */
gulp.task('build:js', function jsBuilder() {
	return gulp.src('./assets/src/js/*.js', {read: false}) // browserify reads file, don't read file twice.
		.pipe(tap(function (file) {
			const bundler = browserify(file.path, { debug: true }).transform(babel, { presets: ['@babel/preset-env'] });
			file.contents = bundler.bundle();
		}).on('error', function(err) { console.error(err); this.emit('end'); }))
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
 *
 * Run:
 *	 - Global command: `gulp build:pot`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:pot`.
 */
gulp.task('build:pot', function potBuilder() {
	const pkg = JSON.parse(fs.readFileSync('./package.json'));
	return gulp.src(['./**/*.php', '!./+(vendor|node_modules|assets|languages)/**'])
		.pipe(gulpWPpot({ domain: pkg.name })
			.on('error', function(err) { console.error(err); this.emit('end'); }))
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
 *
 * Run:
 *	 - Global command: `gulp build:images`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:images`.
 */
gulp.task('build:images', function imageBuilder() {
	return gulp.src(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'])
		.pipe(cached('build:images'))
		.pipe(imagemin()
			.on('error', function(err) { console.error(err); this.emit('end'); }))
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
 *
 * Run:
 *	 - Global command: `gulp build:package:style`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:package:style`.
 */
gulp.task('build:package:style', function packageStyleBuilder(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json'));
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
 *
 * Run:
 *	 - Global command: `gulp build:package:readme:header`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:package:readme:header`.
 */
gulp.task('build:package:readme:header', function packageReadmeHeaderBuilder(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json'));
	const pkgName = pkg.themeName || pkg.name || '';
	var contributorNames = pkg.author.name ? [pkg.author.name] : [];
	if (pkg.contributors && pkg.contributors.length > 0) {
		pkg.contributors.map(function(contributor, i) {
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
 * 
 * Run:
 *	 - Global command: `gulp build:package:readme:content`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:package:readme:content`.
 */
gulp.task('build:package:readme:content', function packageReadmeContentBuilder(done) {
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
 *
 * Run:
 *	 - Global command: `gulp build:package:readme`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:package:readme`.
 */
gulp.task('build:package:readme', gulp.series('build:package:readme:header', 'build:package:readme:content'));

/**
 * Build Package.
 *
 * Process:
 *	 1. Runs the `build:package:style` task. 
 *	 2. Runs the `build:package:readme` task. 
 *
 * Run:
 *	 - Global command: `gulp build:package`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:package`.
 */
gulp.task('build:package', gulp.series('build:package:style', 'build:package:readme'));

/**
 * Zip.
 *
 * Process:
 *	 1. Zips a WordPress friendly theme.
 * 
 * Run:
 *	 - Global command: `gulp zip`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp zip`.
 */
 gulp.task('zip', function zipper(done) {
	const pkg = JSON.parse(fs.readFileSync('./package.json'));
	return gulp.src(['./**', '!./*.zip', '!./package.json', '!./package-lock.json', '!./gulpfile.js', '!./.sasslintrc', '!./.gitignore', '!./.eslint', '!./.editorconfig', '!./node_modules/**'])
		.pipe(GulpZip(pkg.name + '.zip'))
		.pipe(gulp.dest('./'))
		.pipe(debug({ title: 'zip' }));
});

/**
 * Build all assets.
 *
 * Process:
 *	 1. Runs the `build:package` task.
 *	 2. Runs the `build:sass` task.
 *	 3. Runs the `build:js` task.
 *	 4. Runs the `build:pot` task.
 *	 5. Runs the `build:images` task.
 *
 * Run:
 *	 - Global command: `gulp build --env production`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build --env production`.
 *	 - NPM script: `npm run build`.
 */
gulp.task('build', gulp.series('build:package', 'build:pot', 'build:sass', 'build:js', 'build:images', 'build:vendor'));

/**
 * Deploy.
 *
 * Process:
 *	 1. Runs a build.
 *   2. Creates a zip file.
 * 
 * Run:
 *	 - Global command: `gulp deploy`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp deploy`.
 *   - NPM script: `npm run deploy`.
 */
 gulp.task('deploy', gulp.series('build', 'zip'));

/**
 * Lint all SCSS files.
 *
 * Process:
 *	 1. Lints all SCSS and SASS files. 
 *	 2. Logs the linting errors to the console.
 *	 3. Logs processed files to the console. 
 *
 * Run:
 *	 - Global command: `gulp lint:sass`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp lint:sass`.
 */
gulp.task('lint:sass', function sassLinter() {
	return gulp.src(['./assets/src/sass/**/*.s+(a|c)ss'])
		.pipe(sassLint()
			.on('error', function(err) { console.error(err); this.emit('end'); }))
		.pipe(sassLint.format())
		.pipe(debug({ title: 'lint:sass' }));
});

/**
 * Lint all JS files.
 *
 * Process:
 *	 1. Lints all JS files. 
 *	 2. Logs the linting errors to the console.
 *	 3. Logs processed files to the console. 
 *
 * Run:
 *	 - Global command: `gulp lint:js`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp lint:js`.
 */
gulp.task('lint:js', function jsLinter() {
	return gulp.src(['./assets/src/js/**/*.js'])
		.pipe(eslint()
			.on('error', function(err) { console.error(err); this.emit('end'); }))
		.pipe(eslint.format())
		.pipe(debug({ title: 'lint:js' }));
});

/**
 * Lint all assets.
 *
 * Process:
 *	 1. Runs the `lint:sass` task. 
 *	 2. Runs the `lint:js` task. 
 *
 * Run:
 *	 - Global command: `gulp lint`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp lint`.
 */
gulp.task('lint', gulp.series('lint:sass', 'lint:js'));

/**
 * Watch source files and build on change.
 *
 * Process:
 *	 1. Runs the `build:package` task when the package.json file, or source md changes.
 *	 2. Runs the `build:pot` task when the source php changes.
 *	 3. Runs the `lint:sass` and `build:sass` tasks when the source SASS changes.
 *	 4. Runs the `lint:js` and `build:js` tasks when the source JS changes.
 *	 5. Runs the `build:images` task when the source images change.
 *	 6. Runs the `build:css:vendor` task when the vendor css changes.
 *	 7. Runs the `build:js:vendor` task when the vendor js changes.
 *	 8. Runs the `build:images:vendor` task when the vendor images change.
 * 
 * Run: 
 *	 - Global command: `gulp watch`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp watch`.
 */
gulp.task('watch', function watcher() {
	gulp.watch(['./package.json', './assets/src/md/+(DESCRIPTION|FAQ|COPYRIGHT|CHANGELOG).md'], gulp.series('build:package'));
	gulp.watch(['./**/*.php', '!./+(vendor|node_modules|assets|languages)/**'], gulp.series('build:pot'));
	gulp.watch(['./assets/src/sass/**/*.s+(a|c)ss'], gulp.series('lint:sass', 'build:sass'));
	gulp.watch(['./assets/src/js/**/*.js'], gulp.series('lint:js', 'build:js'));
	gulp.watch(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'], gulp.series('build:images'));
	gulp.watch(vendorCss, gulp.series('build:css:vendor'));
	gulp.watch(vendorJs, gulp.series('build:js:vendor'));
	gulp.watch(vendorImages, gulp.series('build:images:vendor'));
});

/**
 * Build all assets (default task). 
 *
 * Process:
 *	 1. Runs the `lint` task.
 *	 2. Runs the `build` task.
 *	 3. Runs the `watch` task.
 * 
 * Run: 
 *	 - Global command: `gulp`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp`.
 *	 - NPM script: `npm start`.
 */
gulp.task('default', gulp.series('lint', 'build', 'watch'));
