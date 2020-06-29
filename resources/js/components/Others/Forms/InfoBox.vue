<template>
    <div class="info-box">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'InfoBox',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .info-box {
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 32px;
        background: $light_background;
        text-align: left;

        p {
            @include font-size(15);
            line-height: 1.6;
        }

        a {
            color: $theme;
            font-weight: 700;
            @include font-size(15);
            line-height: 1.6;
        }

        ul {
            margin-top: 15px;
            display: block;

            li {
                display: block;

                a {
                    display: block;
                }
            }
        }
    }

    @media only screen and (max-width: 960px) {

    }

    @media only screen and (max-width: 690px) {

        .info-box {
            padding: 15px;
        }

    }


    @media (prefers-color-scheme: dark) {

        .info-box {
            background: $dark_mode_foreground;
        }
    }
</style>
