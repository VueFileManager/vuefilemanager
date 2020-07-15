<template>
    <PopupWrapper>

        <div class="popup-image">
            <span class="emoji">{{ emoji }}</span>
        </div>

        <PopupContent class="content">
            <h1 v-if="title" class="title">{{ title }}</h1>
            <p v-if="message" class="message">{{ message }}</p>
        </PopupContent>

        <PopupActions>
            <ButtonBase
                    @click.native="closePopup"
                    button-style="secondary"
                    class="popup-button"
            >{{ $t('global.cancel') }}
            </ButtonBase>
            <ButtonBase
                    @click.native="confirm"
                    :button-style="buttonColor"
                    class="popup-button"
            >{{ $t('global.confirm_action') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/Others/Popup/PopupActions'
    import PopupContent from '@/components/Others/Popup/PopupContent'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {events} from '@/bus'

    export default {
        name: 'ConfirmPopup',
        components: {
            PopupWrapper,
            PopupActions,
            PopupContent,
            ButtonBase,
        },
        data() {
            return {
                confirmationData: [],
                message: undefined,
                title: undefined,
                emoji: undefined,
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
                this.emoji = '🤔'
                this.confirmationData = args.action
                this.buttonColor = 'danger-solid'

                if (args.buttonColor) {
                    this.buttonColor = args.buttonColor
                }
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .popup-image {
        padding-top: 20px;
        text-align: center;
        margin-bottom: 20px;

        .emoji {
            @include font-size(56);
            line-height: 1;
        }
    }

    .content {
        text-align: center;
        padding-bottom: 10px;
        padding-left: 20px;
        padding-right: 20px;

        .title {
            @include font-size(22);
            text-transform: uppercase;
            font-weight: 800;
            color: $text;
        }

        .message {
            @include font-size(16);
            color: #333;
            margin-top: 5px;
        }
    }

    @media only screen and (max-width: 690px){
        .content {
            top: 110px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .content {
            .title {
                color: $dark_mode_text_primary;
            }

            .message {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
