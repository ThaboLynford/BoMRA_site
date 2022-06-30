const path = require('path');
const webpack = require('webpack');

var config = {
    module: {},
};

var yasrAdmin    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-admin': [
            './admin/js/src/yasr-admin-functions.js',
            './admin/js/src/yasr-admin-dashboard.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
});

var yasrPricing    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-pricing-page': [
            './admin/js/src/yasr-pricing-page.js',
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
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
});

var yasrProAdmin = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-pro-admin': [
            './yasr_pro/js/src/yasr-pro-admin.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    }
});

var yasrSettings = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-settings': [
            './admin/js/src/yasr-settings-page.js',
            './admin/js/src/yasr-settings-rankings.js'
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
        extensions: ['*', '.js', '.css']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
});

var yasrClassicEditor = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-editor-screen': [
            './admin/js/src/yasr-editor-screen.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    }
});

var yasrSettingsPro = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-pro-settings': [
            './yasr_pro/js/src/yasr-pro-settings-cr-1.js',
            './yasr_pro/js/src/yasr-pro-settings-cr-2.js',
            './yasr_pro/js/src/yasr-pro-settings-ur-1.js'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
});

var yasrOvMulti    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'overall-multiset': [
            './includes/js/src/shortcodes/overall-multiset.js'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
});

var yasrFrontVV    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'visitorVotes': [
            './includes/js/src/shortcodes/visitorVotes.js'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
});

var yasrRankings  = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'rankings': [
            './includes/js/src/shortcodes/ranking.js'
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
        extensions: ['*', '.js', '.css']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
});

var yasrProFront = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-pro-front': [
            './yasr_pro/js/src/yasr-pro-front.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    }
});

var yasrGuten    = Object.assign({}, config, {
    mode: 'production',
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
});

var yasrGutenPro = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'yasr-pro-gutenberg': [
            './yasr_pro/js/src/guten/yasr-pro-guten-blocks.js',
            './yasr_pro/js/src/guten/yasr-pro-guten-panel.js',
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
        extensions: ['*', '.js']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
});

module.exports   = [yasrAdmin, yasrPricing, yasrProAdmin, yasrSettings, yasrSettingsPro, yasrClassicEditor, yasrOvMulti,
    yasrFrontVV, yasrRankings, yasrProFront, yasrGuten, yasrGutenPro];