<template>
    <transition name="popup">
        <div class="popup" v-if="processingPopup">
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="spinner-wrapper">
                        <Spinner />
                    </div>
                    <h1 class="title">{{ processingPopup.title }}</h1>
                    <p class="message">{{ processingPopup.message }}</p>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import Spinner from './Spinner'
import { mapGetters } from 'vuex'

export default {
    name: 'ProcessingPopup',
    components: {
        Spinner,
    },
    computed: {
        ...mapGetters(['processingPopup']),
    },
}
</script>

<style scoped lang="scss">
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.spinner-wrapper {
    padding-bottom: 90px;
    position: relative;
}

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

.popup-content {
    .title {
        @include font-size(22);
        font-weight: 700;
        color: $text;
    }

    .message {
        @include font-size(16);
        color: #333;
        margin-top: 5px;
    }
}

@media only screen and (max-width: 690px) {
    .popup-wrapper {
        padding: 20px;
        left: 15px;
        right: 15px;
    }

    .popup-content {
        .title {
            @include font-size(19);
        }

        .message {
            @include font-size(15);
        }
    }
}

.dark {
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
        transform: scale(0.7);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
