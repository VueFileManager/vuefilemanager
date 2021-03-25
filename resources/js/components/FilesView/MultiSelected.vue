<template>
    <div class="wrapper">
        <div class="icon-wrapper">   
            <CheckSquareIcon class="icon text-theme" size="21" />
        </div>
        <div class="text">
            <span class="title">{{ title }}</span>
            <span class="count">{{ subtitle }}</span>
        </div>
    </div>
</template>

<script>
import {CheckSquareIcon} from "vue-feather-icons"

export default {
    name: 'MultiSelected',
    props: [
        'title',
        'subtitle'
    ],
    components: {
        CheckSquareIcon
    },
}
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

.wrapper {
    display: flex;
    justify-content: center;

    .text {
        padding-left: 10px;
        width: 100%;
        word-break: break-all;

        .title {
            @include font-size(14);
            font-weight: 700;
            line-height: 1.4;
            display: block;
            color: $text;
        }

        .count {
            @include font-size(12);
            font-weight: 600;
            color: $text-muted;
            display: block;
        }
    }

    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        border: none;

        polyline, path {
            color: inherit;
        }
    }
}

@media (prefers-color-scheme: dark) {
    .wrapper {
        .text {
            .title {
                color: $dark_mode_text_primary;
            }

            .count {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>
