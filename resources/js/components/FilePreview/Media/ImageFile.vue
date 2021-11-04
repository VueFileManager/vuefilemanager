<template>
	<img
		id="printable-file"
		class="file"
		:class="{'file-shadow': ! $isMobile() }"
		:src="imageSource"
	/>
</template>

<script>
	export default {
		name: 'ImageFile',
		props: [
			'file'
		],
		computed: {
			imageSource() {
				let windowWidth = window.innerWidth

				if (windowWidth > 1280)
					return this.file.data.attributes.thumbnail.xl
				else
					return this.file.data.attributes.thumbnail.lg
			}
		}
	}
</script>