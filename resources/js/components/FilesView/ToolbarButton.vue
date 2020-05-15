<template>
    <button class="button" :title="action">
        <corner-down-right-icon v-if="source === 'move'" size="19"></corner-down-right-icon>
        <folder-plus-icon v-if="source === 'folder-plus'" size="19"></folder-plus-icon>
        <trash-2-icon v-if="source === 'trash'" size="19"></trash-2-icon>
        <list-icon v-if="source === 'th-list'" size="19"></list-icon>
        <info-icon v-if="source === 'info'" size="19"></info-icon>
        <grid-icon v-if="source === 'th'" size="19"></grid-icon>
        <link-icon v-if="source === 'share'" size="19"></link-icon>
    </button>
</template>

<script>
    import {FolderPlusIcon, Trash2Icon, GridIcon, ListIcon, InfoIcon, CornerDownRightIcon, LinkIcon} from 'vue-feather-icons'

    export default {
        name: 'ToolbarButton',
        props: ['source', 'action'],
        components: {
            CornerDownRightIcon,
            FolderPlusIcon,
            Trash2Icon,
            ListIcon,
            GridIcon,
            InfoIcon,
            LinkIcon,
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .button {
        height: 42px;
        width: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        border: none;
        @include transition(150ms);
        background: transparent;

        &:hover {
            background: $light_background;

            path, line, polyline, rect, circle {
                @include transition(150ms);
                stroke: $theme;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .button {
            background: transparent;

            &:hover {
                background: $dark_mode_foreground;
            }

            path, line, polyline, rect, circle {
                stroke: $dark_mode_text_primary;
            }
        }
    }
</style>
