module.exports = {
    options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1
    },
    target: {
        files: [
            {
                expand: true,
                cwd: '../Application/views/out/flow/src/css',
                src: ['*.css', '!*.min.css'],
                dest: '../Application/views/out/flow/src/css',
                ext: '.min.css',
                extDot: 'last'
        },
            {
                expand: true,
                cwd: '../Application/views/out/wave/src/css',
                src: ['*.css', '!*.min.css'],
                dest: '../Application/views/out/wave/src/css',
                ext: '.min.css',
                extDot: 'last'
        }

        ]
    }
};
