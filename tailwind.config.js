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
        'light': '#F8F8F8',
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
        'dark-foreground': '#1e2024',
        '2x-dark-foreground': '#282A2F',
        'light-background': '#f4f5f6',
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
