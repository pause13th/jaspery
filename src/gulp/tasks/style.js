const { task, src, dest, parallel } = require("gulp");

var sass = require("gulp-sass");
var gulpRename = require("gulp-rename");
var autoprefixer = require("gulp-autoprefixer");
var sourcemaps = require("gulp-sourcemaps");
// var debug = require("gulp-debug");

const styleSet = require("../../../gulpfile");

styleSet.forEach(set => {
  task(set.taskName, () => {
    return src(set.inputPath + set.inputFile)
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: ["../scss", "../../node_modules"]
        }).on("error", sass.logError)
      )
      .pipe(autoprefixer())
      .pipe(gulpRename(set.rename))
      .pipe(sourcemaps.write("."))
      .pipe(dest(set.outputPath));
  });
});

/**
 * @task gulp style
 */
task(
  "style",
  parallel(
    styleSet.map(item => {
      return item.taskName;
    })
  )
);
