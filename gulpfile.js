// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint   = require('gulp-jshint');
var concat   = require('gulp-concat');
var uglify   = require('gulp-uglify');
var prefixer = require('gulp-autoprefixer');
var rename   = require('gulp-rename');
var watch    = require('gulp-watch');
var sass     = require('gulp-sass');
livereload   = require('gulp-livereload');

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
    .pipe(gulp.dest('dist/js'))
    .pipe(livereload());
});

gulp.task('styles', function() {
  return gulp.src(['src/scss/**/*.scss','src/css/*.css'])
    .pipe(
      sass({ outputStyle: 'compressed' })
      .on('error', function(e) { console.log(e); })
    )
    .pipe(prefixer())
    .pipe(gulp.dest('dist/css'))
    .pipe(rename('style.css'))
    .pipe(livereload());
});

// Watch Files For Changes
gulp.task('watch', function() {
  livereload.listen();
  watch('src/scss/**/*.scss', function(e, cb) {
    gulp.start('styles');
  });
  livereload.listen();
  watch('src/js/*.js', function(e, cb) {
    gulp.start('scripts');
  });
});

// Default Task
gulp.task('default', ['lint', 'scripts', 'styles', 'watch']);
