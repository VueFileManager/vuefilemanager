<template>
    <button class="button-base" :class="buttonStyle" type="button">
        <span v-if="loading" class="icon">
			<FontAwesomeIcon icon="sync-alt" class="sync-alt"/>
		</span>
        <slot v-if="! loading"></slot>
    </button>
</template>

<script>
    export default {
        name: 'ButtonBase',
        props: ['buttonStyle', 'loading']
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .button-base {
        @include font-size(15);
        font-weight: 700;
        cursor: pointer;
        transition: 0.15s all ease;
        border-radius: 8px;
        border: 0;
        padding: 10px 28px;
        display: inline-block;
        white-space: nowrap;

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

        &.danger-solid {
            color: white;
            background: $danger;
        }

        &.secondary {
            color: $text;
            background: $light_background;
        }
    }

    .sync-alt {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
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
