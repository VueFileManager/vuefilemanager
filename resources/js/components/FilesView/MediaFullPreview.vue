<template>
	<div class="media-full-preview" id="mediaPreview" v-if="this.isMedia && fileInfoDetail">
		<div class="file-wrapper-preview" v-for="i in [currentIndex]" :key="i">
			<div class="file-wrapper">
				<audio class="file audio" :class="{ 'file-shadow': !isMobileDevice }" v-if="fileInfoDetail.type == 'audio'" :src="currentFile.file_url" controlsList="nodownload" controls></audio>
				<img v-if="fileInfoDetail.type === 'image' && currentFile.thumbnail" class="file" :class="{ 'file-shadow': !isMobileDevice }" id="image" :src="currentFile.file_url" />
				<div class="video-wrapper" v-if="fileInfoDetail.type === 'video' && currentFile.file_url">
					<video :src="currentFile.file_url" class="video" :class="{ 'file-shadow': !isMobileDevice }" controlsList="nodownload" disablePictureInPicture playsinline controls />
				</div>
			</div>
			<!-- <spinner class="loading-spinner" v-if="!loaded && fileInfoDetail.type === 'image'" /> -->
		</div>
	</div>
</template>

<script>
import { events } from '@/bus'
import { mapGetters } from 'vuex'
import ToolbarButton from '@/components/FilesView/ToolbarButton'
import Spinner from '@/components/FilesView/Spinner'

export default {
	name: 'MediaFullPreview',
	components: { ToolbarButton, Spinner },
	computed: {
		...mapGetters(['fileInfoDetail', 'data']),

		isMobileDevice() {
			return this.$isMobile()
		},

		currentFile: function() {
			return this.sliderFile[Math.abs(this.currentIndex) % this.sliderFile.length]
		},
		isMedia() {
			return this.fileInfoDetail === 'image' || 'video' || 'audio'
		},

		canShareInView() {
			return !this.$isThisLocation(['base', 'participant_uploads', 'latest', 'shared', 'public'])
		}
	},
	data() {
		return {
			currentIndex: 1,
			sliderFile: []
			// loaded: false
		}
	},

	watch: {
		sliderFile() {
			//Close file preview after delete all items
			if (this.sliderFile.length == 0) {
				events.$emit('fileFullPreview:hide')
			}
		},
		currentFile() {
			//Handle actual view image in fileInfoDetail
			if (this.fileInfoDetail) {
				this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile)
				events.$emit('actualShowingImage:ContextMenu', this.currentFile)
				// this.loaded = false
			}
		},
		fileInfoDetail() {
			//File delete handling - show next image after delete one
			if (!this.fileInfoDetail) {
				this.currentIndex = this.currentIndex - 1
				this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile)
				this.sliderFile = []
				this.filteredFiles()
			}
		},
		data(newValue, oldValue) {
			//Move item handling
			if (newValue != oldValue) {
				this.sliderFile = []
				this.filteredFiles()
			}
		}
	},
	methods: {
		filteredFiles() {
			this.data.filter((element) => {
				if (element.type == this.fileInfoDetail.type) {
					this.sliderFile.push(element)
				}
			})
			this.choseActiveFile()
		},
		// onLoaded(event) {
		// 	this.loaded = true
		// },
		choseActiveFile() {
			this.sliderFile.forEach((element, index) => {
				if (element.unique_id == this.fileInfoDetail.unique_id) {
					this.currentIndex = index
				}
			})
		}
	},
	mounted() {
		if (this.sliderFile.length > 1) {
			events.$on('filePreviewAction:next', () => {
				this.currentIndex += 1
				this.slideType = 'next'
				if (this.currentIndex > this.sliderFile.length - 1) {
					this.currentIndex = 0
				}
			})
			events.$on('filePreviewAction:prev', () => {
				this.slideType = 'prev'
				this.currentIndex -= 1
				if (this.currentIndex < 0) {
					this.currentIndex = this.sliderFile.length - 1
				}
			})
		}
	},
	created() {
		this.filteredFiles()
	}
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.media-full-preview {
	height: calc(100% - 72px);
	top: 72px;
	position: relative;
	background-color: white;
}

.navigation-panel {
	width: 100%;
	height: 7%;
	display: flex;
	align-items: center;
	padding: 20px;
	justify-content: space-between;
	background-color: $light-background;
	color: $text;
	.icon-close {
		color: $text;
		@include font-size(21);
		&:hover {
			color: $theme;
		}
	}
}

.loading-spinner {
	position: relative;
}

.file-wrapper-preview {
	width: 100%;
	height: 100%;
	padding: 30px 0px;
	display: flex;
	overflow: hidden;
	justify-content: center;
	align-items: center;
	background-color: white;

	.file-wrapper {
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;

		.file-shadow {
			box-shadow: 0 8px 40px rgba(17, 26, 52, 0.15);
		}

		.file {
			max-width: 100%;
			max-height: 100%;
			align-self: center;
		}

		.audio {
			border-radius: 28px;
		}

        img {
            border-radius: 4px;
        }

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
	}
}

@media (prefers-color-scheme: dark) {
	.file-wrapper-preview {
		background-color: $dark_mode_background;
		.file-wrapper {
			.file-shadow {
				box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
			}
		}
	}
}
</style>