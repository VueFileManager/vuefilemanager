<template>
    <img class="file" :src="src" @error="replaceByOriginal" alt="" />
</template>

<script>
export default {
    name: 'ImageFile',
    props: [
		'file'
	],
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
			if (this.file.data.attributes.mimetype === 'svg') {
				this.src = this.file.data.attributes.file_url
			} else if (window.innerWidth > 1280) {
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
