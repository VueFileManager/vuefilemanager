module.exports = {
	content: [
		'./resources/js/**/*.{js,jsx,ts,tsx,vue}',
		'./resources/views/vuefilemanager/*.blade.php',
	],
	darkMode: 'class',
	theme: {
		debugScreens: {
			position: ['bottom', 'right'],
		},
		screens: {
			'xs': '420px',
			'sm': '640px',
			'md': '768px',
			'lg': '1024px',
			'xl': '1280px',
			'2xl': '1536px',
		},
		/*textColor: {
			'green': '#0ABB87',
			'red': '#fd397a',
			'yellow': '#ffb822',
			'purple': '#9d66fe',
			'blue': '#5578eb',
			'white': '#fff',
		},*/
		extend: {
			scale: {
				'97': '.97',
			},
			borderWidth: {
				'3': '3px',
			},
			borderColor: theme => ({
				'light': '#F3F3F3',
				'red': '#fd397a',
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
				'dark-foreground': '#171819',
				'2x-dark-foreground': '#191b1e',
				'4x-dark-foreground': '#1e2124',
				'light-background': '#f4f5f6',
				'light-300': '#e1e1ef',
			}),
			boxShadow: {
				card: '0 2px 6px 0 rgba(0, 0, 0, 0.04)',
				card_red: '0 2px 6px 0 rgba(253, 57, 122, 0.04)',
			},
			screens: {
				'print': {'raw': 'print'},
			},
		},
	},
	plugins: [
		require('tailwindcss-debug-screens'),
		require('tailwind-scrollbar-hide'),
	],
}
