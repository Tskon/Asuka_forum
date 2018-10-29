const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

module.exports = {
    entry: {
        main: './src/main.js',
    },
    output: {
        filename: 'js/[name].js',
        path: path.resolve(__dirname, 'public'),
        publicPath: './'
    },
    resolve: {
        modules: [ path.join(__dirname, "src"), "node_modules" ]
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
                        options: {}
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
            }
    ]
},
plugins: [
    new CleanWebpackPlugin([ 'public' ]),
    new HtmlWebpackPlugin({
        template: './src/index.html',
        filename: 'index.html',
    }),
    new MiniCssExtractPlugin({
        filename: "css/[name].css",
        chunkFilename: "css/[id].css"
    })
],
    optimization
:
{
    minimizer: [ new OptimizeCSSAssetsPlugin({}) ]
}
,
stats: {
    colors: true,
        chunks
:
    true
}
}
;