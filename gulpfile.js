var gulp = require('gulp');
var clean = require('gulp-clean');
var less = require('gulp-less');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var cssmin = require('gulp-minify-css');
var Elixir = elixir = require('laravel-elixir');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

 /*
  | WARNING
  | if got error: not found: notify-send. just do it
  | sudo yum install libnotify
  | sudo service messagebus start
  |
 */


var $ = Elixir.Plugins;
var Task = Elixir.Task;
var Config = Elixir.config;
var GulpPaths = Elixir.GulpPaths;

Elixir.extend('buildScripts', function(src, output) {
  var paths = new GulpPaths()
    .src(src || '', Config.get('assets.js.folder'))
    .output(output || Config.get('public.js.outputFolder'));

  new Task('scripts', function() {
    this.log(paths.src, paths.output);

    return gulp.src(paths.src.path)
      .pipe(jshint())
      .pipe(jshint.reporter('default'))
      .pipe(uglify())
      .pipe($.if(! paths.output.isDir, $.rename(paths.output.name)))
      .pipe(gulp.dest(paths.output.baseDir));
  })
  .watch(paths.src.path)
  .ignore(paths.output.path);
});

Elixir.extend('buildLess', function(src, output) {
  var paths = new GulpPaths()
    .src(src || '', Config.get('assets.css.less.folder'))
    .output(output || Config.get('public.css.outputFolder'));

  new Task('less', function() {
    this.log(paths.src, paths.output);

    return gulp.src(paths.src.path)
      .pipe(less())
      .pipe(cssmin())
      .pipe($.if(! paths.output.isDir, $.rename(paths.output.name)))
      .pipe(gulp.dest(paths.output.baseDir));
  })
  .watch(paths.src.path)
  .ignore(paths.output.path);
});

elixir(function(mix) {
  mix.buildLess('', 'public/assets/css');
});

elixir(function(mix) {
  mix.buildScripts('', 'public/assets/js');
});

elixir(function(mix) {
  mix.copy('resources/assets/libs', 'public/assets/libs')
      .copy('resources/assets/images', 'public/assets/images');
});

elixir(function(mix) {
    mix.version(['assets/css/**/*.css', 'assets/js/**/*.js']);
});

gulp.task('clean', function () {
  return gulp.src(
    [
      'public/assets/js',
      'public/assets/css',
      'public/assets/images',
      'public/assets/libs',
      'public/assets/build'
    ]
  )
  .pipe(clean({force: true}));
});
