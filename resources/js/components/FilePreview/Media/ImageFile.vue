<template>
    <img id="printable-file" class="file" :class="{'file-shadow': !$isMobile()}" :src="src" @error="replaceByOriginal" />
</template>

<script>
export default {
    name: 'ImageFile',
    props: ['file'],
	watch: {
		'file': function () {
			this.getSrc()
		}
	},
	data() {
		return {
			src: undefined,
		}
	},
	methods: {
		replaceByOriginal() {
			this.src = this.file.data.attributes.file_url
		},
		getSrc() {
			let windowWidth = window.innerWidth

			if (windowWidth > 1280) {
				this.src = this.file.data.attributes.thumbnail.xl
			} else {
				this.src = this.file.data.attributes.thumbnail.lg
			}
		}
	},
	created() {
		this.getSrc()
	}
}
</script>
