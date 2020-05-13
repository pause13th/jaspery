const {
    src,
    dest,
    task,
} = require('gulp');

var modernizr = require('gulp-modernizr');
var gulpRename = require('gulp-rename');

var DIR = require('../../gulpfile');

/**
 * @task modernizr
 */
task('modernizr', function () {
    return src([DIR.modernizr_input_styles, DIR.modernizr_input_scripts])
        .pipe(modernizr({
            "options": [
                "setClasses"
            ]
        }))
        .pipe(gulpRename(DIR.modernizr_name))
        .pipe(dest(DIR.modernizr_output));
});