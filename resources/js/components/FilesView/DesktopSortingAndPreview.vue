<template>
    <div v-if="isVisible" class="sorting-preview">
        <SortingAndPreviewMenu />
    </div>
</template>

<script>
import SortingAndPreviewMenu from '@/components/FilesView/SortingAndPreviewMenu'
import { events } from '@/bus'

    export default {
        name: 'DesktopSortingAndPreview',
        components: {SortingAndPreviewMenu},
        data () {
            return {
                isVisible: false
            }
        },
        mounted () {
            events.$on('sortingAndPreview', (state) => {
                this.isVisible = state
            })

            events.$on('unClick', () => {
                this.isVisible = false
            })
        }
    }
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.sorting-preview {
    min-width: 250px;
    position: absolute;
    z-index: 99;
    box-shadow: $shadow;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    right: 66px;
    top: 63px;

    &.showed {
        display: block;
    }

    /deep/.menu-option {

        &:hover {
            background: $light_background;

            .text-label {
                color: $theme;
            }

            path,
            /deep/ line,
            /deep/ polyline,
            rect,
            circle,
            polygon {
                stroke: $theme !important;
            }
        }
    }
}


@media (prefers-color-scheme: dark) {
    .sorting-preview {
        background: $dark_mode_foreground;

         /deep/ .menu-option {
         &:hover {
                background: rgba($theme, 0.1);
            }
        }
    }
   
}

</style>
