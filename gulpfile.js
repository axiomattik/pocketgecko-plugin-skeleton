const { src, dest, series, parallel, watch } = require('gulp');
const del = require('del');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const path = require('path');
const bs = require('browser-sync').create();
const imagemin = require('gulp-imagemin');
const wpot = require('gulp-wp-pot');
const potomo = require('gulp-potomo');
const jsvalidate = require('gulp-jsvalidate');
const eslint = require('gulp-eslint');
const jsonlint = require('gulp-jsonlint');
const phpcs = require('gulp-phpcs');
const phpmd = require('gulp-phpmd');
const stylelint = require('gulp-stylelint');

const PLUGNAME = path.basename(__dirname);
const BLDROOT = 'bld/' + PLUGNAME + '/';


function compileSCSS(dir) {
  return function compilescss() {
    return src('src/' + dir + '/css/style.scss', {'allowEmpty': true})
      .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
      .pipe(dest('src/' + dir + '/css'));
  };
}


function copyCSS(dir) {
  return function copycss() {
    return src('src/' + dir + '/css/style.css', { 'allowEmpty': true })
      .pipe(dest(BLDROOT + dir + '/css/.'))
      .pipe(bs.stream());
  };
}


function minifyJS(dir) {
  return function minifyjs() {
    return src(['src/' + dir + '/js/**/*.js', '!**/*' + PLUGNAME + '*'],
        {'allowEmpty': true})
      .pipe(concat(PLUGNAME + '.js'))
      .pipe(minify())
      .pipe(dest('src/' + dir + '/js/.'));
  };
}


function copyJS(dir) {
  return function copyjs() {
    return src('src/' + dir + '/js/' + PLUGNAME + '-min.js',
        { 'allowEmpty': true })
      .pipe(dest(BLDROOT + dir + '/js/'))
      .pipe(bs.stream());
  };
}


function copyfiles() {
  let exts = ['php', 'md', 'txt', 'json', 'pot'];
  return src(exts.map((ext) => 'src/**/*.' + ext))
    .pipe(dest(BLDROOT))
    .pipe(bs.stream());
}


function images() {
  let exts = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
  return src(exts.map((ext) => 'src/**/*.' + ext))
    .pipe(imagemin())
    .pipe(dest(BLDROOT))
    .pipe(bs.stream());
}


function i18npot() {
  return src('src/**/*.php')
    .pipe(wpot( {
      domain: PLUGNAME,
    }))
    .pipe(dest('src/i18n/' + PLUGNAME + '.pot'))
    .pipe(bs.stream());
}


function i18npotomo() {
  return src('src/i18n/*.po')
    .pipe(potomo())
    .pipe(dest(BLDROOT + '/i18n/'))
    .pipe(bs.stream());
}


function clean() {
  return del(['bld/**', '!bld']);
}


function dostylelint() {
  return src('src/**/*.scss')
    .pipe(stylelint({
      configFile: ".stylelintrc.json",
      reporters: [ 
        {formatter: 'verbose', console: true } 
      ],
      failAfterError: false,
      fix: true
    }))
    .pipe(dest('src'));
}


function dophpcs() {
  return src('src/**/*.php')
    .pipe(phpcs({
      bin: '/usr/bin/phpcs',
      standard: 'PSR12',
      warningSeverity: 0
    }))
    .pipe(phpcs.reporter('log'));
}


function dophpmd() {
  return src('src/**/*.php')
    .pipe(phpmd({
      bin: '/usr/bin/phpmd',
      format: 'text',
      ruleset: 'cleancode,codesize,design,naming,unusedcode'
    }))
    .on('error', console.error)
}


function dojsonlint() {
  return src('/src/**/*.json')
    .pipe(jsonlint())
    .pipe(jsonlint.reporter());
}


function doeslint() {
  return src('/src/**/*.js')
    .pipe(eslint())
    .pipe(eslint.format())
}

function dojsvalidate() {
  return src('/src/**/*.js')
    .pipe(jsvalidate());
}


function monitor(cb) {
  let filexts = ['php', 'md', 'txt'];
  let imagexts = ['png', 'jpg', 'jpeg', 'gif', 'svg'];

  watch('src/*/css/**/*.scss', exports.css);
  watch(['src/*/js/**/*.js', '!**/*' + PLUGNAME + '*'], exports.js);
  watch(filexts.map((ext) => 'src/**/*.' + ext), exports.copyfiles);
  watch(imagexts.map((ext) => 'src/**/*.' + ext), exports.images);
  watch('src/i18n/**/*', exports.i18n);

  cb();
}


function serve(cb) {
  bs.init({proxy: 'localhost:8000'});
  cb();
}


exports.clean = clean;

exports.lintphp = series(dophpcs, dophpmd);
exports.lintjs = series(dojsonlint, dojsvalidate, doeslint);
exports.lintscss = dostylelint;

exports.lint = series(
  exports.lintphp,
  exports.lintscss,
  exports.lintjs,
);

exports.css = series(
  compileSCSS('public'),
  compileSCSS('admin'), 
  copyCSS('public'),
  copyCSS('admin'),
);

exports.js = series(
  minifyJS('public'),
  minifyJS('admin'),
  copyJS('public'),
  copyJS('admin'),
);

exports.copyfiles = copyfiles;

exports.images = images;

exports.i18n = series(i18npot, i18npotomo);

exports.build = series(
  exports.clean,
  exports.css,
  exports.js,
  exports.copyfiles,
  exports.images,
  exports.i18n
);

exports.watch = monitor;

exports.serve = parallel(exports.watch, serve);

exports.default = series(exports.build, exports.serve);
