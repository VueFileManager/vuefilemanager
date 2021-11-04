<template>
	<div
        v-if="isFullPreview"
        class="file-preview z-40"
        ref="filePreview"
        tabindex="-1"
        @keydown.esc="closeFilePreview"
        @keydown.right="next"
        @keydown.left="prev"
    >
		<FilePreviewToolbar />
		<FilePreviewMedia />
	</div>
</template>

<script>
    import FilePreviewToolbar from '/resources/js/components/FilePreview/FilePreviewToolbar'
    import FilePreviewMedia from '/resources/js/components/FilePreview/FilePreviewMedia'
    import {events} from '/resources/js/bus'

    export default {
        name: 'FilePreview',
        components: {
            FilePreviewToolbar,
            FilePreviewMedia,
        },
        data() {
            return {
                isFullPreview: false
            }
        },
        methods: {
            closeFilePreview() {
                this.isFullPreview = false
				this.$store.commit('FAST_PREVIEW_CLEAR')
            },
            next() {
                events.$emit('file-preview:next')
            },
            prev() {
                events.$emit('file-preview:prev')
            }
        },
        updated() {
            if (this.isFullPreview) {
                this.$refs.filePreview.focus()
            }
        },
        mounted() {
            events.$on('file-preview:show', () => this.isFullPreview = true)
            events.$on('file-preview:hide', () => this.closeFilePreview())
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';

    .file-preview {
        width: 100%;
        height: 100%;
        position: fixed;
        background-color: white;
    }

    .dark {
        .file-preview {
            background-color: $dark_mode_background;
        }
    }
</style>