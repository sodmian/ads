const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    mode: 'development',
    entry: {
        home: './resources/vue/src/home.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
            {
                test: /\.js$/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
                    'css-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                includePaths: ["scss"],
                            },
                        }
                    }
                ]
            }
        ],
    },
    plugins: [
        new VueLoaderPlugin()
    ],
    output: {
        filename: '[name].js',
        path: path.resolve('assets/js'),
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.min.js'
        }
    }
}