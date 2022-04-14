<template>
    <div
        v-if="isFullPreview"
        class="fixed left-0 right-0 top-0 bottom-0 z-40 h-full w-full bg-white dark:bg-dark-background min-w-[320px]"
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
import FilePreviewToolbar from './FilePreviewToolbar'
import FilePreviewMedia from './FilePreviewMedia'
import { events } from '../../bus'

export default {
    name: 'FilePreview',
    components: {
        FilePreviewToolbar,
        FilePreviewMedia,
    },
    data() {
        return {
            isFullPreview: false,
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
        },
    },
    updated() {
        if (this.isFullPreview) {
            this.$refs.filePreview.focus()
        }
    },
    mounted() {
        events.$on('file-preview:show', () => (this.isFullPreview = true))
        events.$on('file-preview:hide', () => this.closeFilePreview())
    },
}
</script>
