const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: {
        index: './src/js/index.js',
        admin: './src/js/admin.js',
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader'
                ]
            },
            {
                test: /\.svg$/i,
                use: ['svgo-loader'],
                type: 'asset/inline'
            },
            {
                test: /\.(png|jpe?g|gif|webp)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'images/[name][contenthash][ext][query]'
                }
            }
        ],
    },
    plugins: [
        new VueLoaderPlugin(),
        new CopyPlugin({
            patterns: [
                {
                    from: path.resolve(__dirname, 'src/assets/static'),
                    to: path.resolve(__dirname, 'gbphp/public/dist/static'),
                    globOptions: { ignore: ['**/.*'] },
                    noErrorOnMissing: true,
                },
                {
                    from: path.resolve(__dirname, 'src/assets/img/products'),
                    to: path.resolve(__dirname, 'gbphp/public/dist/images'),
                    globOptions: { ignore: ['**/.*'] },
                    noErrorOnMissing: true,
                },
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),
            'vue$': 'vue/dist/vue.esm-bundler.js'
        },
        extensions: ['.js', '.vue', '.json']
    },
    output: {
        path: path.resolve(__dirname, 'gbphp/public/dist'),
    },
};