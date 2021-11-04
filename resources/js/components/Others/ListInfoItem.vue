<template>
    <li class="list-info-item">
        <b class="text-theme dark-text-theme">{{ title }}</b>
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .list-info-item {
        display: block;
        padding-bottom: 20px;

        .action-button {
            cursor: pointer;
			display: flex;
			align-items: center;

            .edit-icon {
                display: inline-block;
                margin-left: 7px;
            }
        }

        b {
            display: block;
            @include font-size(13);
            margin-bottom: 2px;
        }

        span {
            display: inline-block;
            @include font-size(14);
            font-weight: bold;
            color: $text;
        }
    }

    .dark {
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
