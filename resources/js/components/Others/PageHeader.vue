<template>
    <div class="page-header">
        <div class="go-back" v-if="canBack" @click="$router.back()">
            <chevron-left-icon size="17"></chevron-left-icon>
        </div>
        <div class="content">
            <h1 class="title">{{ title }}</h1>
        </div>
    </div>
</template>

<script>
    import { ChevronLeftIcon } from 'vue-feather-icons'

    export default {
        name: 'PageHeader',
        props: [
            'title', 'canBack'
        ],
        components: {
            ChevronLeftIcon
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .page-header {
        display: flex;
        align-items: center;
        background: white;
        z-index: 9;
        width: 100%;
        position: sticky;
        top: 0;
        padding: 20px 0;

        .title {
            @include font-size(18);
            font-weight: 700;
            color: $text;
        }

        .go-back {
            margin-right: 10px;
            cursor: pointer;

            svg {
                vertical-align: middle;
                margin-top: -4px;
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .page-header {

            .title {
                @include font-size(18);
            }
        }
    }

    @media only screen and (max-width: 690px) {
        .page-header {
            display: none;
        }
    }

    @media (prefers-color-scheme: dark) {

        .page-header {
            background: $dark_mode_background;

            .title {
                color: $dark_mode_text_primary;
            }

            .icon path {
                fill: $theme;
            }
        }
    }
</style>
