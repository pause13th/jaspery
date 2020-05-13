const {
    src,
    dest,
    task,
} = require('gulp');

var DIR = require('../../gulpfile');

task('fonts', function () {
    return src(DIR.font_fontawesome_input)
        .pipe(dest(DIR.font_fontawesome_output));
});