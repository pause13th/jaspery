const dotenv = require("dotenv").config();
const { task, parallel } = require("gulp");

const styleSet = [
  {
    taskName: "style_theme",
    inputFile: "style.theme.scss",
    inputPath: process.env.SCSS_PATH,
    outputPath:
      process.env.THEME_PATH +
      process.env.THEME_NAME +
      process.env.THEME_STYLE_OUTPUT,
    rename: "style.css"
  },
  {
    taskName: "style_admin",
    inputFile: "style.admin.scss",
    inputPath: process.env.SCSS_PATH,
    outputPath:
      process.env.MUPLUGIN_PATH +
      process.env.MUPLUGIN_NAME +
      process.env.MUPLUGIN_OUTPUT,
    rename: "style.admin.css"
  },
  {
    taskName: "style_login",
    inputFile: "style.login.scss",
    inputPath: process.env.SCSS_PATH,
    outputPath:
      process.env.MUPLUGIN_PATH +
      process.env.MUPLUGIN_NAME +
      process.env.MUPLUGIN_OUTPUT,
    rename: "style.login.css"
  },
  // {
  //   taskName: "style_muplugin",
  //   inputFile: "style.muplugin.scss",
  //   inputPath: process.env.SCSS_PATH,
  //   outputPath:
  //     process.env.MUPLUGIN_PATH +
  //     process.env.MUPLUGIN_NAME +
  //     process.env.MUPLUGIN_OUTPUT,
  //   rename: "style.muplugin.css"
  // }
];
module.exports = styleSet;

require("./src/gulp/tasks/style");
require("./src/gulp/tasks/script");
require("./src/gulp/tasks/watch");

// require('./src/gulp/tasks/build');

// require('./gulp/tasks/fonts');
// require('./gulp/tasks/sprites');

task("default", parallel("style", "script"));
