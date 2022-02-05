<template>
    <transition name="popup">
        <div v-if="isVisibleWrapper" @click.self="closePopup" class="popup z-50 fixed top-0 left-0 right-0 bottom-0 grid h-full overflow-y-auto p-10 lg:absolute">
            <div class="fixed md:relative top-0 bottom-0 left-0 right-0 w-full dark:bg-2x-dark-foreground bg-white m-auto z-10 md:w-[490px] md:rounded-xl shadow-xl">
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
	@media only screen and (max-width: 690px) {
		.popup-enter-active {
			animation: popup-slide-in 0.35s 0.15s ease both;
		}
		.popup-leave-active {
			animation: popup-slide-in 0.15s ease reverse;
		}
	}

	@keyframes popup-slide-in {
		0% {
			transform: translateY(100%);
		}
		100% {
			transform: translateY(0);
		}
	}
</style>
