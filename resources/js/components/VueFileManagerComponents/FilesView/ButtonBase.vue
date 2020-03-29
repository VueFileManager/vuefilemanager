<template>
    <button class="button-base" :class="buttonStyle" type="button">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: 'ButtonBase',
        props: ['buttonStyle']
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .button-base {
        @include font-size(16);
        font-weight: 700;
        cursor: pointer;
        transition: 0.15s all ease;
        border-radius: 8px;
        border: 0;
        padding: 10px 28px;
        display: inline-block;

        &:active {
            transform: scale(0.95);
        }

        &.theme {
            color: $theme;
            background: rgba($theme, .1);
        }

        &.danger {
            color: $danger;
            background: rgba($danger, .1);
        }

        &.secondary {
            color: $text;
            background: $light_background;
        }
    }

    @media (prefers-color-scheme: dark) {

        .button-base {

            &.secondary {
                color: $dark_mode_text_primary;
                background: $dark_mode_foreground;
            }
        }
    }
</style>
