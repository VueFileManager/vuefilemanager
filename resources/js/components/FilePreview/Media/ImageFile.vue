<template>
	<img
		id="printable-file"
		class="file"
		:class="{'file-shadow': ! $isMobile() }"
		:src="file.file_url"
	/>
</template>

<script>
	export default {
		name: 'ImageFile',
		props: [
			'file'
		],
	}
</script>