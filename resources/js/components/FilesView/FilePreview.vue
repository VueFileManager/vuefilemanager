<template>
	<div
        v-if="isFullPreview"
        class="file-preview"
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
    import FilePreviewToolbar from '@/components/FilesView/FilePreviewToolbar'
    import FilePreviewMedia from '@/components/FilesView/FilePreviewMedia'
    import {events} from '@/bus'

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

                events.$emit('showContextMenuPreview:hide')
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
            events.$on('file-preview:show', () => {
                this.isFullPreview = true
            })
            events.$on('file-preview:hide', () => {
                this.isFullPreview = false
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';

    .file-preview {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 7;
        background-color: white;
    }

    @media (prefers-color-scheme: dark) {
        .file-preview {
            background-color: $dark_mode_background;
        }
    }
</style>