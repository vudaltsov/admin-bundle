'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    cssnano = require('gulp-cssnano'),
    rename = require('gulp-rename'),
    livereload = require('gulp-livereload'),
    suppressError = function (error) {
        console.log(error.toString())
        this.emit('end')
    },
    config = require('./gulpconfig.json')

gulp.task('scss', function () {
    return gulp.src(config.scss.src)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .on('error', suppressError)
        .pipe(autoprefixer({
            browsers: ['last 2 versions', 'ie >= 9'],
            flexbox: 'no-2009'
        }))
        .pipe(cssnano({zindex: false}))
        .pipe(sourcemaps.write())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(config.scss.dest, {cwd: config.web_dir}))
        .pipe(livereload())
})

gulp.task('vendor', function () {
    return gulp.src(config.vendor.src, {base: './'})
        .pipe(gulp.dest(config.vendor.dest, {cwd: config.web_dir}))
})

gulp.task('watch', function () {
    livereload.listen()

    gulp.watch(config.watch.scss, ['scss'])
    gulp.watch(config.watch.reload, livereload.reload)
})

gulp.task('default', ['scss', 'vendor', 'watch'])

gulp.task('build', ['scss', 'vendor'])
