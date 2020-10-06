const path = require("path");
const ExtractCssChunks = require("extract-css-chunks-webpack-plugin");

module.exports = {
  entry: {
    main: "./src/js/main.js",
    styles: "./src/sass/styles.scss",
    admin: "./src/sass/admin/admin.scss",
  },
  output: {
    path: path.resolve(__dirname, "assets"),
    filename: "js/[name].bundle.js",
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        loader: "babel-loader",
        options: {
          presets: ["@babel/preset-env"],
          plugins: ["@babel/plugin-transform-runtime"],
        },
      },
      {
        test: /\.scss$/,
        use: [
          ExtractCssChunks.loader,
          {
            loader: "css-loader",
            options: {
              sourceMap: true,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                sourceMap: true,
                plugins: [
                  ["postcss-import", {}],
                  [
                    "postcss-preset-env",
                    {
                      browsers: "> .5%, last 2 versions",
                    },
                  ],
                ],
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.(png|jpg|jpeg)$/,
        use: [
          {
            loader: "file-loader",
            options: {
              publicPath: path.resolve(__dirname, "/assets/img/"),
              outputPath: "img",
              name: "[name].[ext]",
              esModule: false,
            },
          },
          {
            loader: "img-loader",
            options: {
              plugins: [
                require("imagemin-mozjpeg")({
                  progressive: true,
                  arithmetic: false,
                  quality: 80,
                }),
                require("imagemin-pngquant")({
                  floyd: 0.5,
                  speed: 2,
                }),
              ],
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new ExtractCssChunks({
      filename: "/css/[name].css",
    }),
  ],
};
