/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

var path = require('path');
var config = {
  entry: './src/Resources/public/js/c4g_visualization.js',
  mode: "production",
  output: {
    filename: 'c4g_visualization.js',
    path: path.resolve('./src/Resources/public/build/')
  },
  resolve: {
    modules: ['node_modules', 'src/Resources/public/js'],
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
          path.resolve('./src/Resources/public/js'),
        ],
      }
    ]
  }
};

module.exports = config;