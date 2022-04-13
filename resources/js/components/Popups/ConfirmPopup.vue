<template>
    <PopupWrapper>
        <div class="flex h-full -translate-y-7 transform items-center justify-center px-8 text-center md:translate-y-0">
            <div>
                <img src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f914.svg" alt="" class="mx-auto mb-4 w-20 md:mt-6 min-h-[80px]" />

                <h1 v-if="title" class="mb-2 text-2xl font-bold">
                    {{ title }}
                </h1>
                <p v-if="message" class="mb-4 text-sm">
                    {{ message }}
                </p>
            </div>
        </div>

        <PopupActions>
            <ButtonBase @click.native="closePopup" button-style="secondary" class="w-full"
                >{{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase @click.native="confirm" :button-style="buttonColor" class="w-full"
                >{{ $t('yes_iam_sure') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import PopupWrapper from './Components/PopupWrapper'
import PopupActions from './Components/PopupActions'
import ButtonBase from '../UI/Buttons/ButtonBase'
import { events } from '../../bus'

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
        },
    },
    mounted() {
        // Show confirm
        events.$on('confirm:open', (args) => {
            this.title = args.title
            this.message = args.message
            this.confirmationData = args.action
            this.buttonColor = 'danger'

            if (args.buttonColor) {
                this.buttonColor = args.buttonColor
            }
        })
    },
}
</script>
