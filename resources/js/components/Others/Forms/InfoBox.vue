<template>
    <div class="info-box" :class="type">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'InfoBox',
        props: ['type']
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

        &.error {
            background: rgba($danger, 0.1);

            p, a {
                color: $danger;
            }
        }

        p {
            @include font-size(15);
            line-height: 1.6;
            word-break: break-word;
            font-weight: 600;
        }

        b {
            font-weight: 700;
            color: $theme;
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
            background: rgba($yellow, 0.1);

            p {
                color: $yellow;
            }

            ul {

                li {
                    color: $yellow;
                }
            }
        }
    }
</style>
