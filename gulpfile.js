"use strict";

const { src, dest, series, parallel, watch } = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const lzmajs = require('gulp-lzmajs');
const minifyCSS = require('gulp-minify-css');

// Source Maps were too big turned them to false !

function js() {
  return src('src/*.js', { sourcemaps: false })
    .pipe(concat('all.min.js'))
    .pipe(uglify())
    .pipe(lzmajs(9))    
    .pipe(dest('dist', { sourcemaps: false }))
    .on('error', console.error.bind(console));
}

function watch_all() {
    watch('src/*.js', { events: 'all' }, js);
    watch('src/feed/post_styles.js', { events: 'all' }, js_styles);
    watch('src/feed/feed.js', { events: 'all' }, js_feed);
    watch('src/feed/feed.css', { events: 'all' }, css_feed);
}

function js_styles() {
    return src('src/feed/post_styles.js', { sourcemaps: false })
    .pipe(uglify())
    .pipe(lzmajs(9))
    .pipe(concat('post_styles.min.js'))    
    .pipe(dest('dist', { sourcemaps: false }))
    .on('error', console.error.bind(console));
}

function js_feed() {
    return src('src/feed/feed.js', { sourcemaps: false })
    .pipe(uglify())
    .pipe(lzmajs(9))
    .pipe(concat('feed.min.js'))    
    .pipe(dest('dist', { sourcemaps: false }))
    .on('error', console.error.bind(console));
}

function css_feed() {
    return src('src/feed/feed.css')
    .pipe(minifyCSS())
    .pipe(concat('feed.min.css'))
    .pipe(dest('dist'))
    .on('error', console.error.bind(console));
}

exports.js = js;
exports.js_styles = js_styles;
exports.js_feed = js_feed;
exports.css_feed = css_feed;
exports.watch = watch_all;
exports.default = series( parallel(js, js_styles, js_feed, css_feed), watch_all );