<template>
    <div v-if="isVisible" class="sorting-preview">
        <FileSortingOptions />
    </div>
</template>

<script>
import FileSortingOptions from '@/components/FilesView/FileSortingOptions'
import { events } from '@/bus'

    export default {
        name: 'DesktopSortingOptions',
        components: {
            FileSortingOptions
        },
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
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

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
}


@media (prefers-color-scheme: dark) {
    .sorting-preview {
        background: $dark_mode_foreground;
    }
   
}

</style>
