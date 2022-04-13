<template>
    <transition name="popup">
        <div
            v-if="isVisibleWrapper"
            @click.self="closePopup"
            class="popup fixed top-0 left-0 right-0 bottom-0 z-50 grid h-full overflow-y-auto p-10 lg:absolute"
        >
            <div
                class="fixed top-0 bottom-0 left-0 right-0 z-10 m-auto w-full bg-white shadow-xl dark:bg-dark-foreground md:relative md:w-[490px] md:rounded-xl"
            >
                <slot />
            </div>
        </div>
    </transition>
</template>

<script>
import { events } from '../../../bus'

export default {
    name: 'PopupWrapper',
    props: ['name'],
    data() {
        return {
            isVisibleWrapper: false,
        }
    },
    methods: {
        closePopup() {
            events.$emit('popup:close')
        },
    },
    created() {
        // Open called popup
        events.$on('popup:open', ({ name }) => {
            if (this.name === name) this.isVisibleWrapper = true

            if (this.name !== name) this.isVisibleWrapper = false
        })

        // Open called popup
        events.$on('confirm:open', ({ name }) => {
            if (this.name === name) this.isVisibleWrapper = true
        })

        // Close popup
        events.$on('popup:close', () => (this.isVisibleWrapper = false))
    },
}
</script>

<style lang="scss" scoped>
.popup-leave-active {
    animation: popup-slide-in 0.15s ease reverse;
}

@media only screen and (min-width: 960px) {
    .popup-enter-active {
        animation: popup-slide-in 0.25s 0.1s ease both;
    }

    @keyframes popup-slide-in {
        0% {
            opacity: 0;
            transform: translateY(100px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
}

@media only screen and (max-width: 960px) {
    .popup-enter-active {
        animation: popup-slide-in 0.35s 0.15s ease both;
    }

    @keyframes popup-slide-in {
        0% {
            transform: translateY(100%);
        }
        100% {
            transform: translateY(0);
        }
    }
}
</style>
