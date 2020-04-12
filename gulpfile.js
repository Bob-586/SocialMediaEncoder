const { src, dest, parallel } = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const lzmajs = require('gulp-lzmajs');
 
function js() {
  return src('src/*.js', { sourcemaps: true })
    .pipe(concat('all.min.js'))
    .pipe(uglify())
    .pipe(lzmajs(9))    
    .pipe(dest('dist', { sourcemaps: true }))
    .on('error', console.error.bind(console));
}

exports.js = js;
exports.default = parallel(js);