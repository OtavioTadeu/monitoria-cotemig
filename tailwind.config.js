import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Adicionando as cores do COTEMIG
            colors: {
                cotemig: {
                    roxo: '#5b2b82',   // Um roxo profundo característico
                    verde: '#8cc63f',  // Um verde vibrante (tipo "tijolo" de código)
                    escuro: '#1a1a1a', // Preto suave para fundo
                    cinza: '#2d2d2d',  // Cinza para cartões
                }
            }
        },
    },

    plugins: [forms],
};