// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var   sass = require('gulp-sass');

// Lint Task
gulp.task('lint', function() {
  return gulp.src('src/js/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
  return gulp.src('src/js/*.js')
    .pipe(concat('all.js'))
    .pipe(gulp.dest('dist'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist/js'));
});

gulp.task('styles', function() {
  return gulp.src(['src/scss/**/*.scss','src/css/*.css'])
    .pipe(sass())
    .pipe(gulp.dest('dist/css'))
    .pipe(rename('style.css'))
});

// Watch Files For Changes
gulp.task('watch', function() {
  gulp.watch(['src/js/*.js', 'src/styles/**/*.scss'], ['lint', 'scripts', 'styles']);
});

// Default Task
gulp.task('default', ['lint', 'scripts', 'styles', 'watch']);
