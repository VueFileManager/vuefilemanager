<template>
    <MultiSelected :title="title" :subtitle="subtitle" id="multi-select-ui" v-show="isVisible"/>
</template>

<script>
import MultiSelected from '@/components/FilesView/MultiSelected'
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: 'DragUI',
    components: { MultiSelected },
    computed: {
        ...mapGetters(['fileInfoDetail']),
        title() {
            let filesLength = this.fileInfoDetail.length,
                hasDraggedItem = this.fileInfoDetail.includes(this.draggedItem)

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
            let filesLength = this.fileInfoDetail.length,
                hasDraggedItem = this.fileInfoDetail.includes(this.draggedItem)

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
        // Handle Drag & Drop Ghost show
        events.$on('dragstart', data => {
            this.draggedItem = data

            setTimeout(() => {
                this.isVisible = true
            }, 100)
        })

        events.$on('drop', () => {
            this.isVisible = false
        })


    }
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

#multi-select-ui {
    max-width: 300px;
    min-width: 250px;
    position: fixed;
    z-index: 10;
    pointer-events: none;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 7px 25px 1px rgba(0, 0, 0, 0.12);
    background: white;

    /deep/ .text {
        .title {
            color: $text;
        }

        .count {
            color: $text-muted;
        }
    }

    /deep/ .icon-wrapper {
        .icon {
            stroke: $theme;
        }
    }
}

@media (prefers-color-scheme: dark) {
    #multi-select-ui {
        background: $dark_mode_foreground;

        /deep/ .text {
            .title {
                color: $dark_mode_text_primary;
            }

            .count {
                color: $dark_mode_text_secondary;
            }
        }

        /deep/ .icon-wrapper {
            .icon {
                stroke: $theme;
            }
        }
    }
}

</style>
