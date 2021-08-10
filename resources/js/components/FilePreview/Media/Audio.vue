<template>
	<audio
		:class="{'file-shadow': ! this.$isMobile() }"
		class="file audio"
		:src="file.file_url"
		controls>
	</audio>
</template>

<script>
	export default {
		name: 'Audio',
		props: [
			'file'
		],
	}
</script>