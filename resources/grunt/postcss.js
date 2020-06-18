module.exports = {

    options: {
        processors: [
            require('autoprefixer')({browserlist: ['last 2 versions', 'ie 11']})
        ]
    },
    dist: {
        files: [
            {
                expand: true,
                cwd: '../Application/views/out/flow/src/css',
                src: ['*.css', '!*.min.css'],
                dest: '../Application/views/out/flow/src/css',
                ext: '.css',
                extDot: 'last'
        },
            {
                expand: true,
                cwd: '../Application/views/out/wave/src/css',
                src: ['*.css', '!*.min.css'],
                dest: '../Application/views/out/wave/src/css',
                ext: '.css',
                extDot: 'last'
        }
        ]
    }
};
