<template>
    <transition name="popup">
		<div
			v-if="isVisibleWrapper"
			@click.self="closePopup"
			class="fixed top-0 left-0 right-0 bottom-0 z-50 grid h-full overflow-y-auto p-10"
		>
            <div class="fixed top-0 bottom-0 left-0 right-0 z-10 m-auto w-full bg-white shadow-xl dark:bg-dark-foreground md:relative md:w-[490px] md:rounded-xl">
				<div class="flex h-full -translate-y-7 transform items-center justify-center px-8 text-center md:translate-y-0">
					<div>
						<img v-if="isSuccess" src="https://twemoji.maxcdn.com/v/13.1.1/svg/1f609.svg" alt="" class="mx-auto mb-4 w-20 md:mt-6 min-h-[80px]" />
						<img v-if="isAlert" src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f627.svg" alt="" class="mx-auto mb-4 w-20 md:mt-6 min-h-[80px]" />

						<h1 v-if="title" class="mb-2 text-2xl font-bold">
							{{ title }}
						</h1>
						<p v-if="message" class="mb-4 text-sm overflow-anywhere">
							{{ message }}
						</p>
					</div>
				</div>
				<PopupActions>
					<ButtonBase @click.native="closePopup" :button-style="buttonStyle" class="w-full">
						{{ button }}
                    </ButtonBase>
				</PopupActions>
            </div>
        </div>
    </transition>
</template>

<script>
import ButtonBase from '../UI/Buttons/ButtonBase'
import { events } from '../../bus'
import PopupActions from "./Components/PopupActions";

export default {
    name: 'AlertPopup',
    components: {
		PopupActions,
        ButtonBase,
    },
    data() {
        return {
            isVisibleWrapper: false,
            buttonStyle: undefined,
            message: undefined,
            title: undefined,
            button: undefined,
			isSuccess: undefined,
			isAlert: undefined,
        }
    },
    methods: {
        closePopup() {
            events.$emit('popup:close')
        },
    },
    mounted() {
        // Show alert
        events.$on('alert:open', (args) => {
            this.isVisibleWrapper = true
            this.isAlert = true

            this.title = args.title || undefined
            this.message = args.message || undefined

            this.button = this.$te('alerts.error_confirm') ? this.$t('alerts.error_confirm') : 'That’s horrible!'
            this.buttonStyle = 'danger'

            if (args.buttonStyle) {
                this.buttonStyle = args.buttonStyle
            }

            if (args.button) {
                this.button = args.button
            }
        })

        // Show alert
        events.$on('success:open', (args) => {
            this.isVisibleWrapper = true
			this.isSuccess = true

            this.title = args.title
            this.message = args.message

            this.button = this.$t('alerts.success_confirm')
            this.buttonStyle = 'theme'
        })

        // Close popup
        events.$on('popup:close', () => {
            this.isVisibleWrapper = false
            this.isSuccess = undefined
            this.isAlert = undefined
        })
    },
}
</script>

<style scoped lang="scss">

// Animations
.popup-enter-active {
    animation: popup-in 0.35s 0.15s ease both;
}

.popup-leave-active {
    animation: popup-in 0.15s ease reverse;
}

@keyframes popup-in {
    0% {
        opacity: 0;
        transform: scale(0.7);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
