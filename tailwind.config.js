module.exports = {
    purge: ['./src//*.html', './src//*.vue'],
    theme: {
        extend: {
            transitionProperty: {
                height: 'height',
            },
            colors: {
                primary: '#FF9900',
                secondary: '#0091FF',
                // ...
            },
        },
    },
}
