module.exports = {
    purge: ['./src//*.html', './src//*.vue'],
    theme: {
        extend: {
            transitionProperty: {
                height: 'height',
            },
            colors: {
                primary: '#0068b4',
                primaryDark: '#003e84',
                secondary: '#a5a5a5',
                // ...
            },
        },
    },
}
