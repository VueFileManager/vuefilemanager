<template>
	<div
        v-if="showFullPreview"
        class="file-full-preview-wrapper"
        id="fileFullPreview"
        ref="filePreview"
        tabindex="-1"
        @click="closeContextMenu"
        @keydown.esc="(showFullPreview = false), hideContextMenu()"
        @keydown.right="next"
        @keydown.left="prev"
    >
		<FilePreviewNavigationPanel />
		<MediaFullPreview />
	</div>
</template>

<script>
    import FilePreviewNavigationPanel from '@/components/FilesView/FilePreviewNavigationPanel'
    import MediaFullPreview from '@/components/FilesView/MediaFullPreview'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilePreview',
        components: {
            FilePreviewNavigationPanel,
            MediaFullPreview,
        },
        computed: {
            ...mapGetters([
                'fileInfoDetail',
                'data'
            ])
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
                }

                events.$emit('showContextMenuPreview:hide')
            },
            next() {
                events.$emit('file-preview:next')
            },
            prev() {
                events.$emit('file-preview:prev')
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
            events.$on('file-preview:show', () => {
                this.showFullPreview = true
            })
            events.$on('file-preview:hide', () => {
                this.showFullPreview = false
            })
        }
    }
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';

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