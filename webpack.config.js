const path = require("path");
const dotenv = require("dotenv").config();
var webpack = require("webpack");

var config = {
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
  mode: process.env.NODE_ENV,
};

// var themeConfig = Object.assign({}, config, {
//   name: "theme",
//   entry: {
//     theme: process.env.JS_PATH + "/script.theme.js"
//   },
//   output: {
//     path: path.resolve(
//       __dirname,
//       process.env.THEME_PATH + process.env.THEME_NAME + process.env.THEME_OUTPUT
//     ),
//     filename: "script.[name].js"
//   }
// });

var mupluginConfig = Object.assign({}, config, {
  name: "muplugin",
  entry: {
    muplugin: process.env.JS_PATH + "/script.muplugin.js",
  },
  output: {
    path: path.resolve(
      __dirname,
      process.env.MUPLUGIN_PATH +
        process.env.MUPLUGIN_NAME +
        process.env.MUPLUGIN_OUTPUT
    ),
    filename: "script.[name].js",
  },
  plugins: [
    new webpack.ProvidePlugin({
      jQuery: "jquery",
      $: "jquery",
    }),
  ],
});

module.exports = [
  // themeConfig,
  mupluginConfig,
];
