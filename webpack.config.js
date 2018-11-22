const path = require('path');
// const CleanWebpackPlugin = require('clean-webpack-plugin');
// const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin')

module.exports = {
  entry: {
    main: './src/main.js',
  },
  output: {
    filename: 'js/[name].js',
    path: path.resolve(__dirname, 'public_html/wp-content/themes/asuka'),
    // publicPath: './'
  },
  resolve: {
    modules: [ path.resolve(__dirname, "src"), "node_modules" ]
  },
  devtool: 'inline-source-map',
  module: {
    rules: [
      {
        test: /\.(png|svg|gif|jpe?g)$/,
        use: [
          'file-loader?name=img/[name].[ext]'
        ]
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: { publicPath: '../' }
          },
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              plugins: () => [ require('autoprefixer')({
                'browsers': [ '> 1%', 'last 2 versions' ]
              }) ],
            }
          },
          'sass-loader'
        ]
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        }
      }
    ]
  },
  plugins: [
    // new CleanWebpackPlugin([ 'public' ]),
    new CopyWebpackPlugin([ { from: 'src/img/', to: 'img/', toType: 'dir' } ], { /*options*/ }),
    // new HtmlWebpackPlugin({
    //     template: './src/index.html',
    //     filename: 'index.html',
    // }),
    new MiniCssExtractPlugin({
      filename: "css/[name].css",
      // filename: "style.css",
      // chunkFilename: "css/[id].css"
    })
  ],
  optimization:
    {
      minimizer: [ new OptimizeCSSAssetsPlugin({}),
        new TerserPlugin({
          parallel: true,
          terserOptions: {
            ecma: 6,
          },
        }), ]
    }
  ,
  stats: {
    colors: true,
    chunks: true
  }
};