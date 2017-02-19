'use strict';

var plugin = require('./package.json');
var gulp = require('gulp');
var sass = require('gulp-sass');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('makepot', function () {
  return gulp.src(['wc-orders-search-algolia.php', 'inc/**/*.php'])
  .pipe(sort())
  .pipe(wpPot( {
    domain: 'wc-orders-search-algolia',
    package: 'WC Orders Search Algolia' + plugin.version
  } ))
  .pipe(gulp.dest('languages/wc-orders-search-algolia.pot'));
});

gulp.task('sass', function () {
  return gulp.src(['./assets/css/scss/styles.scss'])
    .pipe(sass())
    .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
    }))
    .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:watch', ['sass'], function () {
  gulp.watch(['./assets/css/scss/*.scss'], ['sass']);
});
