<template>
    <div class="popup-header">
        <h1 class="title">{{ title }}</h1>
    </div>
</template>

<script>
    export default {
        name: 'PopupHeader',
        props: [
            'title'
        ]
    }
</script>

<style lang="scss" scoped>
    @import "@assets/app.scss";

    .popup-header {
        padding: 20px;

        .title {
            @include font-size(18);
            font-weight: 700;
            color: $text;
        }

        .message {
            @include font-size(16);
            color: #8b8f9a;
            margin-top: 5px;
        }
    }

    .small {
        .popup-header {
            padding: 15px;
        }
    }

    @media (prefers-color-scheme: dark) {
        .popup-header {
            .title {
                color: $dark_mode_text_primary;
            }

            .message {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
