<template>
	<div class="video-wrapper">
		<video
			:src="file.file_url"
			class="video"
			:class="{'file-shadow': !$isMobile() }"
			controlsList="nodownload"
			disablePictureInPicture
			playsinline
			controls
			autoplay
		/>
	</div>
</template>

<script>
	export default {
		name: 'Video',
		props: [
			'file'
		],
	}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';

	.video-wrapper {
		max-width: 1080px;
		max-height: 100%;

		@media (min-width: 1200px) {
			& {
				max-width: 800px;
			}
		}

		@media (min-width: 1920px) and (max-width: 2560px) {
			& {
				max-width: 1080px;
			}
		}
		@media (min-width: 2560px) and (max-width: 3840px) {
			& {
				max-width: 1440px;
			}
		}
		@media (min-width: 3840px) {
			& {
				max-width: 2160px;
			}
		}

		.video {
			max-width: 100%;
			max-height: 100%;
			align-self: center;
		}
	}
</style>