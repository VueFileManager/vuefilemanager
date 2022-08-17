<template>
    <div v-show="isVisible">
        <!--Overlay component-->
        <div
            @click.capture="hidePopover"
            class="absolute top-12 z-20 w-60 overflow-hidden rounded-xl bg-white shadow-xl dark:bg-dark-foreground select-none"
            :class="{ 'right-0': side === 'left', 'left-0': side === 'right' }"
        >
            <slot />
        </div>

        <!--Clickable layer to close overlays-->
        <div @click="hidePopover" class="fixed top-0 left-0 right-0 bottom-0 z-10 cursor-pointer"></div>
    </div>
</template>

<script>
import { events } from '../../../bus'

export default {
    name: 'PopoverItem',
    props: ['side', 'name'],
    data() {
        return {
            isVisible: false,
        }
    },
    methods: {
        hidePopover() {
            setTimeout(() => (this.isVisible = false), 10)
        },
    },
    mounted() {
        events.$on('popover:open', (name) => {
            if (this.name === name) {
                this.isVisible = !this.isVisible
            }
        })
        events.$on('popover:close', () => this.isVisible = false)
    },
}
</script>
