/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
