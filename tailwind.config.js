const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    theme: {
        extend: {
          colors: {
            naranja: '#FF4700',
            naranjacomplementario:'#FFA37F',
            negro: '#020100',
            gris: '#9C9C9C'
          },
        },
      },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
