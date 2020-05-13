const { task } = require("gulp");

var webpack = require("webpack");

task("script", cb => {
  webpack(require("../../../webpack.config.js"), (err, stats) => {
    if (err) {
      console.log(err.toString());
    }
    // console.log(stats.toString());
    cb();
  });
});
