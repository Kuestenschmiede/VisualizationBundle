var path = require('path');
var config = {
  entry: './Resources/public/js/c4g_visualization.js',
  mode: "development",
  output: {
    filename: 'c4g_visualization.js',
    path: path.resolve('./Resources/public/build/')
  },
  devtool: "inline-source-map",
  resolve: {
    modules: ['node_modules', 'Resources/public/js'],
    extensions: ['.js']
  },
  module: {
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules(?!\/ol)/,
        use: [{
          loader: "babel-loader",
        }],
        include: [
          path.resolve('.'),
          path.resolve('./Resources/public/js'),
        ],
      }
    ]
  }
};

module.exports = config;