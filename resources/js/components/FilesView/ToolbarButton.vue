<template>
  <button class="button hover-text-theme hover-svg-stroke-theme" :title="action">
    <corner-down-right-icon v-if="source === 'move'" size="19" class="hover-text-theme" />
    <download-cloud-icon v-if="source === 'download'" size="19" class="hover-text-theme" />
    <folder-plus-icon v-if="source === 'folder-plus'" size="19" class="hover-text-theme" />
    <user-plus-icon v-if="source === 'user-plus'" size="19" class="hover-text-theme" />
    <zoom-in-icon v-if="source === 'zoom-in'" size="19" />
    <zoom-out-icon v-if="source === 'zoom-out'" size="19" />
    <edit-2-icon v-if="source === 'rename'" size="19" />
    <printer-icon v-if="source === 'print'" size="19" />
    <trash-2-icon v-if="source === 'trash'" size="19" />
    <list-icon v-if="source === 'th-list'" size="19" />
    <info-icon v-if="source === 'info'" size="19" />
    <grid-icon v-if="source === 'th'" size="19" />
    <link-icon v-if="source === 'share'" size="19" />
    <x-icon v-if="source === 'close'" size="19" />
    <cloud-off-icon v-if="source === 'shared-off'" size="19" />
    <sorting-icon v-if="source === 'preview-sorting'" class="preview-sorting"/>
    <CloudPlusIcon v-if="source === 'cloud-plus'" class="preview-sorting"/>
  </button>
</template>

<script>
    import SortingIcon from '/resources/js/components/FilesView/Icons/SortingIcon'
    import CloudPlusIcon from '/resources/js/components/FilesView/Icons/CloudPlusIcon'
    import {
    	UserPlusIcon,
        CornerDownRightIcon,
        DownloadCloudIcon,
        FolderPlusIcon,
        CloudOffIcon,
        PrinterIcon,
        ZoomOutIcon,
        ZoomInIcon,
        Trash2Icon,
        Edit2Icon,
        GridIcon,
        ListIcon,
        InfoIcon,
        LinkIcon,
        XIcon,
    } from "vue-feather-icons";

    export default {
        name: "ToolbarButton",
        props: [
            'source',
            'action'
        ],
        components: {
			CloudPlusIcon,
			UserPlusIcon,
            SortingIcon,
            CornerDownRightIcon,
            DownloadCloudIcon,
            FolderPlusIcon,
            CloudOffIcon,
            PrinterIcon,
            ZoomOutIcon,
            ZoomInIcon,
            Trash2Icon,
            Edit2Icon,
            ListIcon,
            GridIcon,
            InfoIcon,
            LinkIcon,
            XIcon,
        },
    };
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

.preview-sorting {
	transform: scale(1.3);
}

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

    svg {
        color: inherit;

        path, line, polyline, rect, circle {
            color: inherit;
        }
    }

    &:hover {
        background: $light_background;

        path,
        line,
        polyline,
        rect,
        circle {
            @include transition(150ms);
            color: inherit;
        }
    }
}

.dark {
    .button {
        background: transparent;

        &:hover {
            background: $dark_mode_foreground;
        }

        path,
        line,
        polyline,
        rect,
        circle {
            stroke: $dark_mode_text_primary;
        }
    }
}
</style>
