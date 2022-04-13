<template>
    <transition name="vignette">
        <div
            v-if="isVisible"
            class="vignette dark:bg-2x-dark-background bg-dark-background bg-opacity-[0.35] dark:bg-opacity-[0.70]"
            @click="closePopup"
        ></div>
    </transition>
</template>

<script>
import { events } from '../../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'Vignette',
    computed: {
        ...mapGetters(['processingPopup']),
        isVisible() {
            return this.processingPopup || this.isVisibleVignette
        },
    },
    data() {
        return {
            isVisibleVignette: false,
        }
    },
    methods: {
        closePopup() {
            events.$emit('popup:close')
            events.$emit('spotlight:hide')
            events.$emit('mobile-menu:hide')
        },
    },
    created() {
        // Show vignette
        events.$on('popup:open', () => (this.isVisibleVignette = true))
        events.$on('alert:open', () => (this.isVisibleVignette = true))
        events.$on('success:open', () => (this.isVisibleVignette = true))
        events.$on('confirm:open', () => (this.isVisibleVignette = true))

        // Hide vignette
        events.$on('popup:close', () => (this.isVisibleVignette = false))
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.vignette {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    z-index: 40;
}

.vignette-enter-active {
    animation: vignette-in 0.15s linear;
}

.vignette-leave-active {
    animation: vignette-in 0.15s cubic-bezier(0.4, 0, 1, 1) reverse;
}

@keyframes vignette-in {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
</style>
