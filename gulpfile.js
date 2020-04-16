"use strict";

const { src, dest, parallel, watch } = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const lzmajs = require('gulp-lzmajs');

// Source Maps were too big turned them to false !

function js() {
  return src('src/*.js', { sourcemaps: false })
    .pipe(concat('all.min.js'))
    .pipe(uglify())
    .pipe(lzmajs(9))    
    .pipe(dest('dist', { sourcemaps: false }))
    .on('error', console.error.bind(console));
}

function watch_js() {
    watch('src/*.js', { events: 'all' }, js);
}

exports.js = js;
exports.watch_js = watch_js;
exports.default = parallel(js, watch_js);