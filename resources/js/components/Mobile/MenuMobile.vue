<template>
    <transition name="context-menu">
        <div
			v-if="isVisible"
			@click="closeMenu"
			class="fixed pb-4 bottom-0 left-0 right-0 z-50 overflow-hidden dark:bg-2x-dark-foreground bg-white rounded-tl-xl rounded-tr-xl"
		>
            <slot />
        </div>
    </transition>
</template>

<script>
    import {events} from "../../bus";

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
</style>
