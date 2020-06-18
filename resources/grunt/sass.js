const sass = require('node-sass');

module.exports = {
    moduleproduction: {
        options: {
            implementation: sass,
            update: true,
            style: 'compressed'
        },
        files: {
            "../Application/views/out/flow/src/css/afterpay.min.css": "build/scss/afterpay_flow.scss",
            "../Application/views/out/wave/src/css/afterpay.min.css": "build/scss/afterpay_wave.scss"
        }
    }
};

