const path = require("path");
const dotenv = require("dotenv").config();
var webpack = require("webpack");

const postCSSPlugins = [require("postcss-simple-vars"), require("postcss-nested"), require("autoprefixer")];

var config = {
  devServer: {
    before: function (app, server) {
      server._watch("./wp-content/**/*.php");
    },
    contentBase: path.join(__dirname, "/"),
    hot: true,
    port: 3000,
    // host: '0.0.0.0',
    proxy: {
      "/": {
        context: () => true,
        target: "http://localhost/jaspery",
        changeOrigin: true,
      },
    },
  },
  mode: process.env.NODE_ENV,
  // watch: true,
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: [
          "style-loader",
          "css-loader?url=false",
          {
            loader: "postcss-loader",
            options: {
              parser: require("postcss-comment"),
              plugins: postCSSPlugins,
            },
          },
        ],
      },
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
};

var mupluginConfig = Object.assign({}, config, {
  name: "muplugin",
  entry: {
    muplugin: process.env.JS_PATH + "/script.muplugin.js",
  },
  output: {
    filename: "script.[name].js",
    path: path.resolve(__dirname, process.env.MUPLUGIN_PATH + process.env.MUPLUGIN_NAME + process.env.MUPLUGIN_OUTPUT),
  },
  /* plugins: [
    new webpack.ProvidePlugin({
      jQuery: "jquery",
      $: "jquery",
    }),
  ], */
});

module.exports = [
  // themeConfig,
  mupluginConfig,
];
