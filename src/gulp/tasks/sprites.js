const {
    src,
    dest,
    task,
    series,
    parallel
} = require('gulp');

var svgSprite = require('gulp-svg-sprite');
var gulpRename = require('gulp-rename');
var del = require('del');
var svg2png = require('gulp-svg2png');

var DIR = require('../../gulpfile');

var config = {
    shape: {
        spacing: {
            padding: 1
        }
    },
    mode: {
        css: {
            variables: {
                // udemy - chapter 56 - 4min
                // https://www.udemy.com/git-a-web-developer-job-mastering-the-modern-workflow/learn/v4/t/lecture/5579382?start=225
                replaceSvgWithPng: function () {
                    return function (sprite, render) {
                        return render(sprite).split('.svg').join('.png')
                    }
                }
            },
            sprite: 'sprites.svg',
            render: {
                css: {
                    template: DIR.sprites_css_input
                    // template: './gulp/template/sprites.css'
                }
            }
        }
    }
}

task('begin-sprite', function () {
    return del(
        [DIR.temp_folder + '/css', DIR.sprites_output], {
            force: true
        }
    );
});

task('create-sprite', function () {
    return src(DIR.sprites_svg)
        .pipe(svgSprite(config))
        .pipe(dest(DIR.temp_folder));
});

task('copy-sprite-png-to-dest', function () {
    return src(DIR.sprites_svg_temp)
        .pipe(svg2png())
        .pipe(dest(DIR.sprites_output))
});

task('copy-sprite-to-dest', function () {
    return src(DIR.sprites_svg_png_temp)
        .pipe(dest(DIR.sprites_output));
});

task('output-sprite-css', function () {
    return src(DIR.sprites_css_temp)
        .pipe(gulpRename('_sprite.css'))
        .pipe(dest(DIR.sprites_css_output));
});

task('clean-sprite-css', function () {
    return del(DIR.temp_folder + '/css');
});

/**
 * sprites
 */
task('sprites', series('begin-sprite', 'create-sprite', 'copy-sprite-png-to-dest', parallel('copy-sprite-to-dest', 'output-sprite-css'), 'clean-sprite-css'));