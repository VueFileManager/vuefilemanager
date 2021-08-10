<template>
    <TitlePreview
		icon="check-square"
		:title="title"
		:subtitle="subtitle"
		id="drag-ui"
		v-show="isVisible"
	/>
</template>

<script>
import TitlePreview from '/resources/js/components/FilesView/TitlePreview'
import { mapGetters } from 'vuex'
import { events } from '/resources/js/bus'

export default {
    name: 'DragUI',
    components: {
		TitlePreview
	},
    computed: {
        ...mapGetters([
			'clipboard'
		]),
        title() {
            let filesLength = this.clipboard.length,
                hasDraggedItem = this.clipboard.includes(this.draggedItem)

            // Title for multiple selected items
            if (filesLength > 1 && hasDraggedItem) {
                return this.$t('file_detail.selected_multiple')
            }

            // Title for single item
            if ((filesLength < 2 || !hasDraggedItem) && this.draggedItem) {
                return this.draggedItem.name
            }
        },
        subtitle() {
            let filesLength = this.clipboard.length,
                hasDraggedItem = this.clipboard.includes(this.draggedItem)

            // Subtitle for multiple selected items
            if (filesLength > 1 && hasDraggedItem) {
                return filesLength + ' ' + this.$tc('file_detail.items', filesLength)
            }

            if ((filesLength < 2 || !hasDraggedItem) && this.draggedItem) {

                // Subtitle for single folder
                if (this.draggedItem.type === 'folder') {
                    return this.draggedItem.items == 0 ? this.$t('folder.empty') : this.$tc('folder.item_counts', this.draggedItem.items)
                }

                // Subtitle for single file
                if (this.draggedItem !== 'folder' && this.draggedItem.mimetype) {
                    return '.' + this.draggedItem.mimetype
                }
            }
        }
    },
    data() {
        return {
            isVisible: false,
            draggedItem: undefined
        }
    },
    created() {
        events.$on('dragstart', data => {
            this.draggedItem = data

            setTimeout(() => {
                this.isVisible = true
            }, 100)
        })

        events.$on('drop', () => this.isVisible = false)
    }
}
</script>

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

#drag-ui {
    max-width: 300px;
    min-width: 250px;
    position: fixed;
    z-index: 10;
    pointer-events: none;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 7px 25px 1px rgba(0, 0, 0, 0.12);
    background: white;
}

.dark-mode {
    #drag-ui {
        background: $dark_mode_foreground;
    }
}

</style>
