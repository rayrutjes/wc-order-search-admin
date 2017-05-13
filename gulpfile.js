'use strict';

var plugin = require('./package.json');
var gulp = require('gulp');
var sass = require('gulp-sass');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('makepot', function () {
  return gulp.src(['algolia-woocommerce-order-search-admin.php', 'inc/**/*.php'])
  .pipe(sort())
  .pipe(wpPot( {
    domain: 'algolia-woocommerce-order-search-admin',
    package: 'WC Orders Search Algolia' + plugin.version
  } ))
  .pipe(gulp.dest('languages/algolia-woocommerce-order-search-admin.pot'));
});

gulp.task('sass', function () {
  return gulp.src(['./css/scss/styles.scss'])
    .pipe(sass())
    .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
    }))
    .pipe(gulp.dest('./css'));
});

gulp.task('sass:watch', ['sass'], function () {
  gulp.watch(['./css/scss/*.scss'], ['sass']);
});
