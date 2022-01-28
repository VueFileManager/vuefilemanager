<template>
    <PopupWrapper>
        <div class="text-center h-full flex items-center justify-center px-8 transform md:translate-y-0 -translate-y-7">
			<div>
				<img src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f914.svg" alt="" class="w-20 mx-auto md:mt-6 mb-4">

				<h1 v-if="title" class="text-2xl font-bold mb-2">
					{{ title }}
				</h1>
				<p v-if="message" class="text-sm mb-4">
					{{ message }}
				</p>
			</div>
        </div>

        <PopupActions>
            <ButtonBase
                    @click.native="closePopup"
                    button-style="secondary"
                    class="w-full"
            >{{ $t('global.cancel') }}
            </ButtonBase>
            <ButtonBase
                    @click.native="confirm"
                    :button-style="buttonColor"
                    class="w-full"
            >{{ $t('global.confirm_action') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import PopupWrapper from "./PopupWrapper";
    import PopupActions from "./PopupActions";
    import ButtonBase from "../../FilesView/ButtonBase";
    import {events} from '../../../bus'

    export default {
        name: 'ConfirmPopup',
        components: {
            PopupWrapper,
            PopupActions,
            ButtonBase,
        },
        data() {
            return {
                confirmationData: [],
                message: undefined,
                title: undefined,
                buttonColor: undefined,
            }
        },
        methods: {
            closePopup() {
                events.$emit('popup:close')
            },
            confirm() {
                // Close popup
                events.$emit('popup:close')

                // Confirmation popup
                events.$emit('action:confirmed', this.confirmationData)

                // Clear confirmation data
                this.confirmationData = []
            }
        },
        mounted() {
            // Show confirm
            events.$on('confirm:open', args => {
                this.title = args.title
                this.message = args.message
                this.confirmationData = args.action
                this.buttonColor = 'danger'

                if (args.buttonColor) {
                    this.buttonColor = args.buttonColor
                }
            })
        }
    }
</script>
