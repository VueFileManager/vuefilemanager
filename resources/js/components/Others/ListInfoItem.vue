<template>
    <li class="list-info-item">
        <b>{{ title }}</b>
        <span v-if="content">{{ content }}</span>
        <slot></slot>
    </li>
</template>

<script>
    export default {
        name: 'ListInfoItem',
        props: ['title', 'content']
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .list-info-item {
        display: block;
        padding-bottom: 20px;

        .action-button {
            cursor: pointer;

            .edit-icon {
                display: inline-block;
                margin-left: 3px;
            }
        }

        b {
            display: block;
            @include font-size(13);
            color: $theme;
            margin-bottom: 2px;
        }

        span {
            display: inline-block;
            @include font-size(14);
            font-weight: bold;
            color: $text;
        }
    }

    @media (prefers-color-scheme: dark) {
        .list-info-item {

            span {
                color: $dark_mode_text_primary
            }

            .action-button {

                .icon {
                    color: $dark_mode_text_primary;
                }
            }
        }
    }
</style>
