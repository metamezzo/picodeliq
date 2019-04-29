const path = require('path');

const ExtractTextPlugin = require("extract-text-webpack-plugin");

const CleanWebpackPlugin = require("clean-webpack-plugin");

const CopyPlugin = require("copy-webpack-plugin");

const extractSass = new ExtractTextPlugin({
  filename: "css/[name].css",
  disable: false,
  allChunks: true
});

const cleanDest = new CleanWebpackPlugin(['dist']);

const copyImages = new CopyPlugin([
  {
    from: './assets/images',
    to: './images'
  }
]);

module.exports = {
  entry: {
    main: './assets/js/main.js'
  },
  output: {
    filename: 'js/[name].bundle.js',
    path: path.resolve( __dirname, 'dist' )
  },
  module: {
    loaders: [
      {
        test: /.js$/,
        loaders: 'buble',
        include: path.join( __dirname, 'asset/js' )
      },
      {
        test: /.scss$/,
        use: extractSass.extract(
          {
            use: [ "css-loader", "sass-loader" ],
            fallback: "style-loader"
          }
        )
      },
      {
        test: /.svg$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: 'images/[name].[ext]',
              publicPath: '../'
            }
          },
          {
            loader: 'svgo-loader',
            options: {
              plugins: [
                {removeTitle: false},
                {convertColors: {shorthex: false}},
                {convertPathData: false},
              ]
            }
          }
        ]
      }
    ] // loaders
  }, // module
  plugins: [
    cleanDest,
    extractSass,
    copyImages
  ]
};