const { src, dest, parallel } = require('gulp');
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

exports.js = js;
exports.default = parallel(js);