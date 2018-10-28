const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: {
        main: './src/index.js',
        // styles: './src/scss/main.scss'
    },
    output: {
        filename: 'js/[name].js',
        path: path.resolve(__dirname, 'public'),
    },
    resolve: {
        modules: [path.join(__dirname, "src"), "node_modules"]
    },
    module: {
        rules: [
            {
                test: /\.(png|svg|gif|jpe?g)$/,
                use: [
                    'file-loader?name=img/[name].[ext]'
                ]
            },
            {
                test: /\.html$/,
                use: [
                    'file-loader?name=[name].[ext]'
                ]
            }
        ]
    },
    plugins: [
        new CopyWebpackPlugin([{ from: './src/index.html', to: 'index.html' }], {})
    ],
    stats: {
        colors: true,
        chunks: true
    }
};