const {
    src,
    dest,
    task,
    series,
    parallel
} = require('gulp');

var imagemin = require('gulp-imagemin');
var del = require('del');
var sourcemaps = require('gulp-sourcemaps');
var cssnano = require('gulp-cssnano');
var uglify = require('gulp-uglify');
var browserSync = require('browser-sync').create();

// for static html
// var usemin = require('gulp-usemin');
// var rev = require('gulp-rev');

var DIR = require('../../gulpfile');

task('clear-dist-images-folder', function () {
    return del(DIR.images_output, {
        force: true
    });
});

task('images', function () {
    return src(DIR.images_jpg_png_gif, {
            // base: DIR.images_folder_base
        })
        .pipe(imagemin({
            progressive: true,
            interlaced: true,
            multipass: true
        }))
        .pipe(dest(DIR.images_output));
});

task('optimize-admin-style', function () {
    return src(DIR.admin_style_output + admin_style_name)
        .pipe(sourcemaps.init())
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.admin_style_output))
});
task('optimize-login-style', function () {
    return src(DIR.login_style_output + login_style_name)
        .pipe(sourcemaps.init())
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.login_style_output))
});
task('optimize-plugin-style', function () {
    return src(DIR.plugin_style_output + plugin_style_name)
        .pipe(sourcemaps.init())
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.plugin_style_output))
});
task('optimize-theme-style', function () {
    return src(DIR.theme_style_output + theme_style_name)
        .pipe(sourcemaps.init())
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.theme_style_output))
});

task('optimize-admin-script', function () {
    return src(DIR.admin_script_output + admin_script_name)
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.admin_script_output))
});
task('optimize-plugin-script', function () {
    return src(DIR.plugin_script_output + plugin_script_name)
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.plugin_script_output))
});
task('optimize-theme-script', function () {
    return src(DIR.theme_script_output + theme_script_name)
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(dest(DIR.theme_script_output))
});

task('preview-product', function () {
    browserSync.init({
        notify: true,
        proxy: DIR.browser_sync_preview_url,
        ghostMode: false,
        files: [
            DIR.watch_php
        ]
    });
});

task("default", series('style', 'script', 'sprites', 'images'));
