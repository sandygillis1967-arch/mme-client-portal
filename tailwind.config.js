/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                navy: {
                    DEFAULT: '#1B2A5E',
                    50:  '#e8eaf4',
                    100: '#c5cae4',
                    200: '#9fa8d2',
                    300: '#7886bf',
                    400: '#5b6db2',
                    500: '#3d54a4',
                    600: '#374d9b',
                    700: '#2f4390',
                    800: '#283a85',
                    900: '#1B2A5E',
                },
                gold: {
                    DEFAULT: '#F5C518',
                    50:  '#fffde7',
                    100: '#fff9c4',
                    200: '#fff59d',
                    300: '#fff176',
                    400: '#ffee58',
                    500: '#ffeb3b',
                    600: '#fdd835',
                    700: '#f9c825',
                    800: '#F5C518',
                    900: '#f0b800',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
