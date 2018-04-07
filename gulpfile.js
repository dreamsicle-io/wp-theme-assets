'use-strict';

const del = require('del');
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const gulp = require('gulp');
const debug = require('gulp-debug');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const babel = require('babelify');
const uglify = require('gulp-uglify');
const imagemin = require('gulp-imagemin');
const rename = require('gulp-rename');
const wpPot = require('gulp-wp-pot');
const eslint = require('gulp-eslint');
const sassLint = require('gulp-sass-lint');

/**
 * Build Sass.
 *
 * Process:
 *	 1. Imports all SASS modules to file.
 *	 2. Compiles the SASS to CSS.
 *	 3. Minifies the file.
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
	return gulp.src(['./assets/src/sass/**/*.s+(a|c)ss'])
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(sass({ includePaths: ['node_modules'], outputStyle: 'compressed', cascade: false })
			.on('error', function(err) { console.error(err); this.emit('end'); }))
		.pipe(autoprefixer({ browsers: ['last 5 versions', 'ie >= 9'] }))
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/css'))
		.pipe(debug({ title: 'build:sass' }));
});

/**
 * Build Site JS.
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
 *	 - Global command: `gulp build:js:site`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js:site`.
 */
gulp.task('build:js:site', function siteJsBuilder() {
	const bundler = browserify('./assets/src/js/site.js', { debug: true }).transform(babel, { presets: ['env'] });
	return bundler.bundle()
		.on('error', function(err) { console.error(err); this.emit('end'); })
		.pipe(source('site.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/js'))
		.pipe(debug({ title: 'build:js:site' }));
});

/**
 * Build Admin JS.
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
 *	 - Global command: `gulp build:js:admin`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js:admin`.
 */
gulp.task('build:js:admin', function adminJsBuilder() {
	const bundler = browserify('./assets/src/js/admin.js', { debug: true }).transform(babel, { presets: ['env'] });
	return bundler.bundle()
		.on('error', function(err) { console.error(err); this.emit('end'); })
		.pipe(source('admin.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/js'))
		.pipe(debug({ title: 'build:js:admin' }));
});

/**
 * Build Customizer JS.
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
 *	 - Global command: `gulp build:js:customizer`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js:customizer`.
 */
gulp.task('build:js:customizer', function customizerJsBuilder() {
	const bundler = browserify('./assets/src/js/customizer.js', { debug: true }).transform(babel, { presets: ['env'] });
	return bundler.bundle()
		.on('error', function(err) { console.error(err); this.emit('end'); })
		.pipe(source('customizer.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./assets/dist/js'))
		.pipe(debug({ title: 'build:js:customizer' }));
});

/**
 * Build All JS.
 *
 * Process:
 *	 1. Runs the `build:js:site` task. 
 *	 2. Runs the `build:js:admin` task. 
 *	 3. Runs the `build:js:customizer` task. 
 *
 * Run:
 *	 - Global command: `gulp build:js`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:js`.
 */
gulp.task('build:js', gulp.series('build:js:site', 'build:js:admin', 'build:js:customizer'));

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
	return gulp.src(['./**/*.php'])
		.pipe(wpPot({ domain: 'wpmdc' })
			.on('error', function(err) { console.error(err); this.emit('end'); }))
		.pipe(gulp.dest('./languages/wpmdc.pot'))
		.pipe(debug({ title: 'build:pot' }));
});

/**
 * Build Images.
 *
 * Process:
 *	 1. Process and optimize all images. 
 *	 3. Writes optimized images to the build directory.
 *	 4. Logs created files to the console.
 *
 * Run:
 *	 - Global command: `gulp build:pot`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build:pot`.
 */
gulp.task('build:images', function imageBuilder() {
	return gulp.src(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'])
		.pipe(imagemin()
			.on('error', function(err) { console.error(err); this.emit('end'); }))
		.pipe(gulp.dest('./assets/dist/images'))
		.pipe(debug({ title: 'build:images' }));
});

/**
 * Build all assets.
 *
 * Process:
 *	 1. Runs the `build:sass` task.
 *	 2. Runs the `build:js` task.
 *	 3. Runs the `build:pot` task.
 *	 4. Runs the `build:images` task.
 *
 * Run:
 *	 - Global command: `gulp build`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp build`.
 *	 - NPM script: `npm run build`.
 */
gulp.task('build', gulp.series('build:sass', 'build:js', 'build:pot', 'build:images'));

/**
 * Clean build CSS.
 *
 * Process:
 *	 1. Deletes the CSS build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:css`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:css`.
 *	 - NPM script: `npm run clean:css`.
 */
gulp.task('clean:css', function cssCleaner(done) {
	return del(['./assets/dist/css'], done());
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
	return del(['./assets/dist/js'], done());
});

/**
 * Clean localization build files.
 *
 * Process:
 *	 1. Deletes the localization build directory.
 *
 * Run:
 *	 - Global command: `gulp clean:js`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean:js`.
 */
gulp.task('clean:pot', function potCleaner(done) {
	return del(['./languages'], done());
});

/**
 * Clean build assets.
 *
 * Process: 
 *	 1. Deletes the build directory.
 *	 2. Deletes the localization directory.
 *
 * Run:
 *	 - Global command: `gulp clean`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp clean`.
 *	 - NPM script: `npm run clean`.
 */
gulp.task('clean', function cleaner(done) {
	return del(['./assets/dist', './languages'], done());
});

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
	return gulp.src(['./assets/src/sass/**/*.s+(as|sc)s'])
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
 *	 - NPM script: `npm run lint`.
 */
gulp.task('lint', gulp.series('lint:sass', 'lint:js'));

/**
 * Watch source files and build on change.
 *
 * Process:
 *	 1. Runs the `build:sass` task when the source Sass changes.
 *	 2. Runs the `build:js` task when the source JS changes.
 *	 3. Runs the `build:pot` task when the source php changes.
 *	 3. Runs the `build:images` task when the source images change.
 * 
 * Run: 
 *	 - Global command: `gulp watch`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp watch`.
 *	 - NPM script: `npm run watch`.
 */
gulp.task('watch', function watcher() {
	gulp.watch(['./assets/src/sass/**/*.s+(a|c)ss'], gulp.series('lint:sass', 'build:sass'));
	gulp.watch(['./assets/src/js/**/*.js'], gulp.series('lint:js', 'build:js'));
	gulp.watch(['./**/*.php'], gulp.series('build:pot'));
	gulp.watch(['./assets/src/images/**/*.+(jpg|jpeg|png|svg|gif)'], gulp.series('build:images'));
});

/**
 * Build all assets (default task). 
 *
 * Process:
 *	 1. Runs the `build` task.
 *	 2. Runs the `watch` task.
 * 
 * Run: 
 *	 - Global command: `gulp`.
 *	 - Local command: `node ./node_modules/gulp/bin/gulp`.
 *	 - NPM script: `npm start`.
 */
gulp.task('default', gulp.series('build', 'watch'));
