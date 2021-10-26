module.exports = {
  purge: [],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    extend: {
      borderWidth: {
        '3': '3px',
      },
      borderColor: theme => ({
        'dark-background': '#151515',
        'dark-secondary': '#1e2024',
      }),
      fontSize: {
        tiny: ['11px', '16px'],
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
