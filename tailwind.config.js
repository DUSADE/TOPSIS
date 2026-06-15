import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Sora', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'serif'],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: 'rgb(var(--color-primary) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary) / <alpha-value>)',
                accent: 'rgb(var(--color-accent) / <alpha-value>)',
                background: 'rgb(var(--color-background) / <alpha-value>)',
                foreground: 'rgb(var(--color-foreground) / <alpha-value>)',
                muted: {
                    DEFAULT: 'rgb(var(--color-muted) / <alpha-value>)',
                    foreground: 'rgb(var(--color-muted-foreground) / <alpha-value>)',
                },
                card: {
                    DEFAULT: 'rgb(var(--color-card) / <alpha-value>)',
                    foreground: 'rgb(var(--color-card-foreground) / <alpha-value>)',
                },
            },
            borderRadius: {
                'xl': 'var(--radius-standard)',
            },
            animation: {
                'pulse-soft': 'pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'spin-slow': 'spin 8s linear infinite',
            },
            keyframes: {
                'pulse-soft': {
                    '0%, 100%': { opacity: 1 },
                    '50%': { opacity: 0.8 },
                }
            },
            boxShadow: {
                'glass': '0 20px 50px -24px rgba(120, 53, 15, 0.30)',
            },
        },
    },
    plugins: [],
};
