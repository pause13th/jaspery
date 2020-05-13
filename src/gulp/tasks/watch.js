const { src, task, series, parallel } = require("gulp");

const path = require("path");
var watch = require("gulp-watch");
var browserSync = require("browser-sync").create();
// var notify = require("gulp-notify");

const styleSet = require("../../../gulpfile");

function browser_sync() {
  browserSync.init({
    open: true,
    injectChanges: true,
    proxy: process.env.SITE_URL,
    notify: false,
    files: [path.resolve() + "/wp-content/**/*.*"],
    // server: {
    //     baseDir: './dist/'
    // },
    port: process.env.PORT,
  });
}

task("watch", parallel(browser_sync, watch_files));

function watch_files() {
  // watch style
  watch(
    process.env.SCSS_PATH + "**/*.scss",
    parallel(
      styleSet.map(item => {
        return "watch:" + item.taskName;
      })
    )
  );

  // watch script -> reload if there changes
  watch(
    process.env.JS_PATH + "**/*.js",
    series("script", callback => {
      browserSync.reload();
      callback();
    })
  );
}

/**
 * @task watch style
 */
styleSet.forEach(set => {
  task(
    "watch:" + set.taskName,
    series(set.taskName, () => {
      return src(set.outputPath + set.rename).pipe(browserSync.stream());
    })
  );
});
