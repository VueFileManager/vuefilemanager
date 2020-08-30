<template>
	<div
		v-if="showFullPreview"
		class="file-full-preview-wrapper"
		id="fileFullPreview"
		ref="filePreview"
		tabindex="-1"
		@click="closeContextMenu"
		@keydown.esc=";(showFullPreview = false), hideContextMenu()"
		@keydown.right="next"
		@keydown.left="prev"
	>
		<FilePreviewNavigationPanel />
		<MediaFullPreview />
		<FilePreviewActions />
	</div>
</template>

<script>
import { events } from '@/bus'
import { mapGetters } from 'vuex'

import MediaFullPreview from '@/components/FilesView/MediaFullPreview'
import FilePreviewActions from '@/components/FilesView/FilePreviewActions'
import FilePreviewNavigationPanel from '@/components/FilesView/FilePreviewNavigationPanel'

export default {
	name: 'FileFullPreview',
	components: {
		MediaFullPreview,
		FilePreviewNavigationPanel,
		FilePreviewActions
	},
	computed: {
		...mapGetters(['fileInfoDetail', 'data'])
	},
	data() {
		return {
			showFullPreview: false
		}
	},
	methods: {
		closeContextMenu(event) {
			if ((event.target.parentElement.id || event.target.id) === 'fast-preview-menu') {
				return
			} else {
				events.$emit('showContextMenuPreview:hide')
			}
		},
		next: function() {
			events.$emit('filePreviewAction:next')
		},
		prev: function() {
			events.$emit('filePreviewAction:prev')
		},
		hideContextMenu() {
			events.$emit('showContextMenuPreview:hide')
		}
	},

	updated() {
		//Focus file preview for key binding
		if (this.showFullPreview) {
			this.$refs.filePreview.focus()
		}
	},
	mounted() {
		events.$on('fileFullPreview:show', () => {
			this.showFullPreview = true
		})
		events.$on('fileFullPreview:hide', () => {
			this.showFullPreview = false

            events.$emit('hide:mobile-navigation')
        })
	}
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';

.file-full-preview-wrapper {
	width: 100%;
	height: 100%;
	position: absolute;
	z-index: 7;
	background-color: white;
}

@media (prefers-color-scheme: dark) {
	.file-full-preview-wrapper {
		background-color: $dark_mode_background;
	}
}
</style>