<template>
    <transition name="popup">
        <div class="popup" @click.self="closePopup" v-if="isVisibleWrapper">
            <div class="popup-wrapper">
                <slot></slot>
            </div>
        </div>
    </transition>
</template>

<script>
    import {events} from '@/bus'

    export default {
        name: 'PopupWrapper',
        props: [
            'name'
        ],
        data() {
            return {
                isVisibleWrapper: false,
            }
        },
        methods: {
            closePopup() {
                events.$emit('popup:close')
            }
        },
        created() {

            // Open called popup
            events.$on('popup:open', ({name}) => {

                if (this.name === name)
                    this.isVisibleWrapper = true
            })

            // Close popup
            events.$on('popup:close', () => {

                // Close popup
                this.isVisibleWrapper = false
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .popup {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 20;
        overflow-y: auto;
        display: grid;
        padding: 40px;
        height: 100%;
    }

    .popup-wrapper {
        box-shadow: $light_mode_popup_shadow;
        border-radius: 8px;
        background: white;
        margin: auto;
        width: 480px;
        z-index: 12;
    }

    // Desktop, tablet
    .medium, .large {

        // Animations
        .popup-enter-active {
            animation: popup-in 0.35s 0.15s ease both;
        }

        .popup-leave-active {
            animation: popup-in 0.15s ease reverse;
        }
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
    @keyframes popup-slide-in {
        0% {
            transform: translateY(100%);
        }
        100% {
            transform: translateY(0);
        }
    }

    @media only screen and (max-width: 690px) {

        .popup {
            overflow: hidden;
        }

        .popup-wrapper {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            transform: translateY(0) scale(1);
            box-shadow: none;
            width: 100%;
            border-radius: 0px;
        }

        // Animations
        .popup-enter-active {
            animation: popup-slide-in 0.35s 0.15s ease both;
        }

        .popup-leave-active {
            animation: popup-slide-in 0.15s ease reverse;
        }
    }

    @media (prefers-color-scheme: dark) {
        .popup-wrapper {
            background: $dark_mode_background;
            box-shadow: $dark_mode_popup_shadow;
        }
    }

    @media (prefers-color-scheme: dark) and (max-width: 690px) {
        .popup-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
