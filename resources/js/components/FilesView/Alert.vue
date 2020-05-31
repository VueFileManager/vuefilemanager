<template>
    <transition name="popup">
        <div @click.self="closePopup" class="popup" v-if="isVisibleWrapper">
            <div class="popup-wrapper">
                <div class="popup-image">
                    <span class="emoji">{{ emoji }}</span>
                </div>
                <div class="popup-content">
                    <h1 v-if="title" class="title">{{ title }}</h1>
                    <p v-if="message" class="message">{{ message }}</p>
                </div>
                <div class="popup-actions">
                    <ButtonBase
                            @click.native="closePopup"
                            :button-style="buttonStyle"
                            class="action-confirm"
                    >{{ button }}
                    </ButtonBase>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {events} from '@/bus'

    export default {
        name: 'AlertPopup',
        components: {
            ButtonBase
        },
        data() {
            return {
                isVisibleWrapper: false,
                buttonStyle: undefined,
                message: undefined,
                title: undefined,
                button: undefined,
                emoji: undefined,
            }
        },
        methods: {
            closePopup() {
                events.$emit('popup:close')
            }
        },
        mounted() {
            // Show alert
            events.$on('alert:open', args => {
                this.isVisibleWrapper = true

                this.title = args.title
                this.message = args.message

                this.button = this.$t('alerts.error_confirm')
                this.emoji = '😢😢😢'
                this.buttonStyle = 'danger-solid'

                if (args.emoji) {
                    this.emoji = args.emoji
                }
            })

            // Show alert
            events.$on('success:open', args => {
                this.isVisibleWrapper = true

                this.title = args.title
                this.message = args.message

                this.button = this.$t('alerts.success_confirm')
                this.emoji = '🥳🥳🥳'
                this.buttonStyle = 'theme'

                if (args.emoji) {
                    this.emoji = args.emoji
                }
            })

            // Close popup
            events.$on('popup:close', () => {
                this.isVisibleWrapper = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .popup {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 20;
        overflow: auto;
        height: 100%;
    }

    .popup-wrapper {
        z-index: 12;
        position: absolute;
        left: 0;
        right: 0;
        max-width: 480px;
        top: 50%;
        transform: translateY(-50%) scale(1);
        margin: 0 auto;
        padding: 20px;
        box-shadow: $light_mode_popup_shadow;
        border-radius: 8px;
        text-align: center;
        background: white;
    }

    .popup-image {
        margin-bottom: 30px;

        .emoji {
            @include font-size(56);
            line-height: 1;
        }
    }

    .popup-content {
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

    .popup-actions {
        margin-top: 30px;

        .action-confirm {
            width: 100%;
        }
    }

    @media only screen and (max-width: 690px) {
        .popup-wrapper {
            padding: 40px 20px 20px;
            left: 15px;
            right: 15px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .popup-wrapper {
            background: $dark_mode_background;
        }

        .popup-content {
            .title {
                color: $dark_mode_text_primary;
            }

            .message {
                color: $dark_mode_text_secondary;
            }
        }
    }

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
