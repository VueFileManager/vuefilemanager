<template>
    <div class="popup-content" :class="type">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'PopupContent',
        props: [
            'type'
        ]
    }
</script>

<style lang="scss" scoped>
    @import "@assets/app.scss";

    .popup-content {

        &.height-limited {
            height: 400px;
            overflow-y: auto;
        }
    }

    .small {

        .popup-content {
            top: 57px;
            bottom: 72px;
            position: absolute;
            left: 0;
            right: 0;
            height: initial;
        }
    }

    @media (prefers-color-scheme: dark) {

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
</style>
