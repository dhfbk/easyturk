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
            opacity: {
                '10': '.1',
                '20': '.2',
                '30': '.3',
                '40': '.4',
                '50': '.5',
                '60': '.6',
                '70': '.7',
                '80': '.8',
                '85': '.85',
                '90': '.9',
            },
        },
    },
}
