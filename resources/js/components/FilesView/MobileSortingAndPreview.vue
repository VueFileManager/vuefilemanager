<template>
    <transition v-if="isVisible" name="preview-menu" >
        <SortingAndPreviewMenu class="options"/>
    </transition>
</template>

<script>
import SortingAndPreviewMenu from '@/components/FilesView/SortingAndPreviewMenu'
import { events } from '@/bus'

    export default {
        name: 'MobileSortingAndPreview',
        components: {SortingAndPreviewMenu},
        data () {
            return {
                isVisible: false
            }
        },
        mounted () {
            events.$on('mobileSortingAndPreview', (state) => {
                this.isVisible = state
            })
        }
    }
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.options {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99;
    overflow: hidden;
    background: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

@media (prefers-color-scheme: dark) {
    .options {
        background: $dark_mode_foreground;
    }
}


// Transition
.preview-menu-enter-active,
.fade-enter-active {
    transition: all 200ms;
}

.preview-menu-leave-active,
.fade-leave-active {
    transition: all 200ms;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.preview-menu-enter,
.preview-menu-leave-to {
    opacity: 0;
    transform: translateY(100%);
}

.preview-menu-leave-active {
    position: absolute;
}

</style>
