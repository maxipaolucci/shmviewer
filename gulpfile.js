var gulp = require('gulp'),
    runSequence = require('run-sequence'), //for run task in order and not in parallel
    del = require('del'), //for remove files or directories
    inject = require('gulp-inject'), //for do injection into a file of some resources
    angularFileSort = require('gulp-angular-filesort'), //for order angular resourses when injected in html
    serve = require('gulp-serve'), //for start a server on a specified path
    jshint = require('gulp-jshint'), //for js hint
    sass = require('gulp-sass'); //for compile sass

var config = require('./gulp/gulp.config.js');

gulp.task('default', function(callback) {
    runSequence('ang-build', 'ang-watch', 'ang-serve', callback);
});

/**
 * run the server pointing to the build directory
 */
gulp.task('ang-serve', serve(config.build_dir));

/**
 * Generates the angular app build. 
 * First deletes the current build and then generates a new one
 */
gulp.task('ang-build', function(callback) {
    runSequence('ang-clean-build', 'ang-sass', 'ang-copy-build', callback);
});

/**
 * Deletes the current angular build
 */
gulp.task('ang-clean-build', function() {
    return del([config.build_dir], {force: true});
});

/**
 * Inject all the js resources into index.html and copy it into the build directory
 */
gulp.task('ang-inject-index', function() {
    
    var tplSrc = ['./app/build/js/libs/*.js', './app/build/js/shmApp.js', './app/build/js/services/*.js', 
        './app/build/components/**/*.js'];
    return gulp.src('./app/index.html')
        .pipe(inject(gulp.src(tplSrc).pipe(angularFileSort()), {ignorePath: 'app/build'}))
        .pipe(inject(gulp.src('./app/build/css/**/*.css'), {ignorePath: 'app/build'}))
        .pipe(gulp.dest('./app/build'));
});


/**
 * Copy all the resources for the angular build
 */
gulp.task('ang-copy-build', ['ang-copy-components', 'ang-copy-js', 'ang-copy-views', 'ang-copy-css', 'ang-copy-extrafiles']);

/**
 * Copy angular components into build dir
 */
gulp.task('ang-copy-components', function() {
    return gulp.src(config.ang_files.components_all).pipe(gulp.dest('./app/build/components'));
});

/**
 * Copy angular js dir into build js dir
 */
gulp.task('ang-copy-js', function() {
    gulp.src('./app/js/**/*.json').pipe(gulp.dest('./app/build/js'));
    return gulp.src('./app/js/**/*.js').pipe(gulp.dest('./app/build/js'));
});

/**
 * Copy angular views into build views dir
 */
gulp.task('ang-copy-views', function() {
    return gulp.src('./app/views/**/*.html').pipe(gulp.dest('./app/build/views'));
});

/**
 * Copy angular css resources and font resources into build css dir
 */
gulp.task('ang-copy-css', function() {
    gulp.src('./app/css/**/*.css').pipe(gulp.dest('./app/build/css'));
    return gulp.src('./app/css/font-awesome-4.3.0/fonts/*.*').pipe(gulp.dest('./app/build/css/font-awesome-4.3.0/fonts'));
});

/**
 * Copy angular specific files into build dir
 */
gulp.task('ang-copy-extrafiles', function() {
    return gulp.src('./app/*.html').pipe(gulp.dest('./app/build/'));
});

/**
 * Checks the sintax of the app js files (not in build)
 */
gulp.task('ang-lint', function() {
    return gulp.src(config.ang_files.src_js).pipe(jshint()).pipe(jshint.reporter('default'));
});

/**
 * starts a watcher looking for any changes in the app js files
 */
gulp.task('ang-watch', function() {
    gulp.watch(config.ang_files.src_sass, ['ang-build']);
    gulp.watch(config.ang_files.src_js, ['ang-lint', 'ang-build']);
});


/**
 * compile all sass resources into css ones.
 */
gulp.task('ang-sass', function () {
  return gulp.src(config.ang_files.src_sass)
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./app/css'));
});
