<template>
    <button class="mobile-action-button">
        <FontAwesomeIcon class="icon" :icon="icon"></FontAwesomeIcon>
        <span class="label">{{ text }}</span>
    </button>
</template>

<script>
    export default {
        name: 'MobileActionButton',
        props: [
            'icon', 'text'
        ],
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";


    .mobile-action-button {
        background: $light_background;
        margin-right: 15px;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        border: none;

        .icon {
            margin-right: 10px;
            @include font-size(14);
        }

        .label {
            @include font-size(14);
            font-weight: 700;
            color: $text;
        }
    }

    @media (prefers-color-scheme: dark) {
        .mobile-action-button {
            background: $dark_mode_foreground;

            .icon path {
                fill: $theme;
            }

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
