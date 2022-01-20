<template>
    <div v-if="emoji">
        <div
			v-if="!isApple"
			v-html="transferEmoji"
			style="font-size: inherit; transform: scale(0.95)"
		></div>
        <div
			v-if="isApple"
			style="font-size: inherit"
		>
			{{ emoji.char }}
		</div>
    </div>
</template>

<script>
	import twemoji from 'twemoji'

	export default {
		name: 'Emoji',
		props: [
			'emoji',
		],
		data() {
			return {
				isApple: false,
				sizeClass: undefined,
			}
		},
		computed: {
			transferEmoji() {
				//if (! this.apple) return

				return twemoji.parse(this.emoji.char, {
					folder: 'svg',
					ext: '.svg',
					attributes: () => ({
						loading: 'lazy'
					})
				})
			}
		},
	}
</script>

<style lang="css">
	.emoji {
		height: 1em;
		width: 1em;
		font-size: inherit;
	}
</style>