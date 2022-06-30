//see here for help https://stackoverflow.com/questions/37656592/define-global-variable-with-webpack

const path    = require('path');
const webpack = require('webpack');

module.exports = {
    mode: 'development',
    entry: {
        './guten/blocks/yasrGutenUtils'      : './admin/js/src/guten/blocks/yasrGutenUtils.js',
        './guten/blocks/overallRating'       : './admin/js/src/guten/blocks/overallRating.js',
        './guten/blocks/visitorVotes'        : './admin/js/src/guten/blocks/visitorVotes.js',
        './guten/blocks/rankings'            : './admin/js/src/guten/blocks/rankings.js',
        './guten/blocks/noStarsRankings'     : './admin/js/src/guten/blocks/noStarsRankings.js',
        './guten/yasr-guten-misc' : [
            './admin/js/src/guten/blocks/deprecated/deprecated_blocks.js',
            './admin/js/src/guten/yasr-guten-panel.js'
        ]
    },
    module: {
        rules: [
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: ['babel-loader']
            }
        ]
    },
    resolve: {
        extensions: ['*', '.js'],
        alias: {
            'yasrGutenUtils': path.resolve('admin/js/src/guten/blocks/yasrGutenUtils.js')  // <-- When you build or restart dev-server, you'll get an error if the path to your utils.js file is incorrect.
        }
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
    plugins: [
        new webpack.ProvidePlugin({
            'yasrGutenUtils': 'yasrGutenUtils'
        })
    ]
};