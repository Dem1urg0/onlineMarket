const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const webpack = require('webpack');

module.exports = merge(common, {
    mode: 'development',
    devtool: 'source-map',
    devServer: {
        static: {
            directory: path.join(__dirname, 'gbphp/public'),
        },
        hot: true,
        port: 8081,
        proxy: [
            {
                context: ['/api'],
                target: 'http://localhost:80',
                changeOrigin: true,
            }
        ],
        devMiddleware: {
            publicPath: '/dist/'
        }
    },
    output: {
        filename: '[name].js',
        publicPath: 'http://localhost:8081/dist/'
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css'
        }),
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: true,
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: true
        })
    ],
    optimization: {
        minimize: false,
    }
});