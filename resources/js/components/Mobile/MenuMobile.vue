<template>
    <transition name="context-menu">
        <div v-if="isVisible" @click="closeMenu" class="options">
            <slot></slot>
        </div>
    </transition>
</template>

<script>
    import {events} from '@/bus'

    export default {
        name: 'MenuMobile',
        props: [
            'name'
        ],
        data() {
            return {
                isVisible: false,
            }
        },
        methods: {
            closeMenu() {
                this.isVisible = false

                events.$emit('mobile-menu:hide')
            }
        },
        created() {
            events.$on('mobile-menu:show', name => {
                if (name === this.name)
                    this.isVisible = !this.isVisible
            })

            events.$on('mobile-menu:hide', () => this.isVisible = false)
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vuefilemanager/_variables";
    @import "@assets/vuefilemanager/_mixins";

    .options {
        position: absolute;
        padding-bottom: 12px;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99;
        overflow: hidden;
        background: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;

        &.showed {
            display: block;
        }
    }

    // Transition
    .context-menu-enter-active,
    .fade-enter-active {
        transition: all 200ms;
    }

    .context-menu-leave-active,
    .fade-leave-active {
        transition: all 200ms;
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
        position: absolute;
    }

    @media (prefers-color-scheme: dark) {

        .options {
            background: $dark_mode_foreground;
        }
    }
</style>
