<template>
    <div
        v-show="isVisible"
        id="drag-ui"
        class="pointer-events-none fixed z-20 w-64 rounded-xl bg-white p-5 shadow-lg dark:bg-dark-foreground"
    >
        <TitlePreview icon="check-square" :title="title" :subtitle="subtitle" />
    </div>
</template>

<script>
import TitlePreview from '../Labels/TitlePreview'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'

export default {
    name: 'DragUI',
    components: {
        TitlePreview,
    },
    computed: {
        ...mapGetters(['clipboard']),
        title() {
            let filesLength = this.clipboard.length,
                hasDraggedItem = this.clipboard.includes(this.draggedItem)

            // Title for multiple selected items
            if (filesLength > 1 && hasDraggedItem) {
                return this.$t('selected_multiple')
            }

            // Title for single item
            if ((filesLength < 2 || !hasDraggedItem) && this.draggedItem) {
                return this.draggedItem.data.attributes.name
            }
        },
        subtitle() {
            let filesLength = this.clipboard.length,
                hasDraggedItem = this.clipboard.includes(this.draggedItem)

            // Subtitle for multiple selected items
            if (filesLength > 1 && hasDraggedItem) {
                return filesLength + ' ' + this.$tc('items', filesLength)
            }

            if ((filesLength < 2 || !hasDraggedItem) && this.draggedItem) {
                // Subtitle for single folder
                if (this.draggedItem.data.type === 'folder') {
                    return this.draggedItem.items == 0
                        ? this.$t('empty')
                        : this.$tc('folder.item_counts', this.draggedItem.items)
                }

                // Subtitle for single file
                if (this.draggedItem.data.type !== 'folder' && this.draggedItem.data.attributes.mimetype) {
                    return '.' + this.draggedItem.data.attributes.mimetype
                }
            }
        },
    },
    data() {
        return {
            isVisible: false,
            draggedItem: undefined,
        }
    },
    created() {
        events.$on('dragstart', (data) => {
            this.draggedItem = data

            setTimeout(() => {
                this.isVisible = true
            }, 100)
        })

        events.$on('drop', () => (this.isVisible = false))
    },
}
</script>
