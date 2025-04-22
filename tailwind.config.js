/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        fontFamily: {
            // Jadikan Poppins sebagai font default sans-serif
            sans: [
                'Poppins',
                'ui-sans-serif',
                'system-ui',
                '-apple-system',
                'BlinkMacSystemFont',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji'
            ],

            // Anda bisa menambahkan variasi lain jika perlu
            serif: ['ui-serif', 'Georgia', 'Cambria', /*...*/],
            mono: ['ui-monospace', 'SFMono-Regular', /*...*/],
        },
        extend: {
            fontFamily: {
                // Atau bisa juga dengan pendekatan extend saja
                'body': ['Poppins', 'sans-serif'],
                'display': ['Poppins', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}

