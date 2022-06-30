const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pro-admin': [
            './yasr_pro/js/src/yasr-pro-admin.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};