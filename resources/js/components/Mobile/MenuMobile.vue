<template>
    <div>
        <transition name="context-menu">
            <div
                v-show="isVisible"
                @click="closeMenu"
                class="fixed bottom-0 left-0 right-0 z-50 overflow-hidden rounded-tl-xl rounded-tr-xl bg-white pb-4 dark:bg-2x-dark-foreground"
            >
                <slot />
            </div>
        </transition>
        <transition name="vignette">
            <div
                v-if="isVisible"
                @click="closeMenu"
                class="fixed left-0 right-0 top-0 bottom-0 z-[49] bg-dark-background bg-opacity-[0.35] dark:bg-opacity-[0.45]"
            ></div>
        </transition>
    </div>
</template>

<script>
import { events } from '../../bus'

export default {
    name: 'MenuMobile',
    props: ['name'],
    data() {
        return {
            isVisible: false,
        }
    },
    methods: {
        closeMenu() {
            this.isVisible = false

            events.$emit('mobile-menu:hide')
        },
    },
    created() {
        events.$on('mobile-menu:show', (name) => {
            if (name === this.name) this.isVisible = !this.isVisible
        })

        events.$on('mobile-menu:hide', () => (this.isVisible = false))
    },
}
</script>

<style scoped lang="scss">
// Transition
.context-menu-enter-active,
.fade-enter-active {
    transition: all 300ms;
}

.context-menu-leave-active,
.fade-leave-active {
    transition: all 300ms;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.context-menu-enter,
.context-menu-leave-to {
    opacity: 0;
    transform: translateY(100%);
}

.context-menu-leave-active {
    position: fixed;
}

.vignette-enter-active {
    animation: vignette-in 0.15s cubic-bezier(0.4, 0, 1, 1);
}

.vignette-leave-active {
    animation: vignette-in 0.15s linear reverse;
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
