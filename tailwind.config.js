module.exports = {
  purge: { content: ['./resources/js/**/*.{js,jsx,ts,tsx,vue}',] },
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    debugScreens: {
      position: ['bottom', 'right'],
    },
    extend: {
      scale: {
        '97': '.97',
      },
      borderWidth: {
        '3': '3px',
      },
      borderColor: theme => ({
        'light': '#F3F3F3',
        'dark-background': '#151515',
        'dark-secondary': '#1e2024',
      }),
      fontSize: {
        tiny: ['11px', '16px'],
      },
      color: {
        'dark-foreground': '#1e2024',
      },
      backgroundColor: theme => ({
        'dark-background': '#151515',
        'dark-foreground': '#161718',
        '2x-dark-foreground': '#191b1e',
        'light-background': '#f4f5f6',
        'light-300': '#e1e1ef',
      }),
      boxShadow: {
        card: '0 2px 6px 0 rgba(0, 0, 0, 0.04)',
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('tailwindcss-debug-screens'),
  ],
}
