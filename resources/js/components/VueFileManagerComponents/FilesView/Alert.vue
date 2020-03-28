<template>
    <transition name="vignette">
        <div class="popup" v-show="isVisibleWrapper">
            <transition name="popup">
                <div v-show="isVisiblePopup" class="popup-wrapper">
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
            </transition>
            <div class="popup-vignette" @click="closePopup"></div>
        </div>
    </transition>
</template>

<script>
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
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
                isVisiblePopup: false,
                message: undefined,
                title: undefined,
                button: undefined,
                emoji: undefined,
            }
        },
        methods: {
            closePopup() {
                // Emit event
                events.$emit('alert:close')

                // Hide popup wrapper
                this.isVisibleWrapper = false
                this.isVisiblePopup = false
            }
        },
        mounted() {
            // Show alert
            events.$on('alert:open', args => {
                this.isVisibleWrapper = true
                this.isVisiblePopup = true

                this.title = args.title
                this.message = args.message

                this.button = 'That’s horrible!'
                this.emoji = '😢😢😢'
                this.buttonStyle = 'danger'

                if (args.emoji) {
                    this.emoji = args.emoji
                }
            })

            // Show alert
            events.$on('success:open', args => {
                this.isVisibleWrapper = true
                this.isVisiblePopup = true

                this.title = args.title
                this.message = args.message

                this.button = 'Awesome!'
                this.emoji = '🥳🥳🥳'
                this.buttonStyle = 'theme'

                if (args.emoji) {
                    this.emoji = args.emoji
                }
            })

            // Close popup
            events.$on('popup:close', () => {
                this.isVisiblePopup = false
                this.isVisibleWrapper = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .popup {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 20;
        overflow: auto;

        .popup-vignette {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background: rgba(17, 20, 29, 0.5);
        }
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
        padding: 40px;
        box-shadow: 0 7px 250px rgba(25, 54, 60, 0.2);
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
            color: #8b8f9a;
            margin-top: 5px;
        }
    }

    .popup-actions {
        margin-top: 30px;

        .action-confirm {
            width: 100%;
        }
    }

    // Small screen size
    .small {
        .popup-wrapper {
            padding: 40px 20px 20px;
            left: 15px;
            right: 15px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .popup .popup-vignette {
            background: $dark_mode_vignette;
        }

        .popup-wrapper {
            background: $dark_mode_foreground;
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
            transform: translateY(-50%) scale(0.7);
        }
        100% {
            opacity: 1;
            transform: translateY(-50%) scale(1);
        }
    }

    .vignette-enter-active {
        animation: vignette-in 0.15s ease;
    }

    .vignette-leave-active {
        animation: vignette-in 0.15s 0.15s ease reverse;
    }

    @keyframes vignette-in {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
