<template>
    <button
        class="group inline-flex h-[42px] w-[42px] cursor-pointer items-center justify-center rounded-lg hover:bg-light-background dark:hover:bg-2x-dark-foreground"
        :title="action"
    >
        <corner-down-right-icon v-if="source === 'move'" size="19" class="vue-feather group-hover-text-theme" />
        <download-cloud-icon v-if="source === 'download'" size="19" class="vue-feather group-hover-text-theme" />
        <folder-plus-icon v-if="source === 'folder-plus'" size="19" class="vue-feather group-hover-text-theme" />
        <user-plus-icon v-if="source === 'user-plus'" size="19" class="vue-feather group-hover-text-theme" />
        <zoom-in-icon v-if="source === 'zoom-in'" size="19" class="vue-feather group-hover-text-theme" />
        <zoom-out-icon v-if="source === 'zoom-out'" size="19" class="vue-feather group-hover-text-theme" />
        <edit-2-icon v-if="source === 'rename'" size="19" class="vue-feather group-hover-text-theme" />
        <printer-icon v-if="source === 'print'" size="19" class="vue-feather group-hover-text-theme" />
        <trash-2-icon v-if="source === 'trash'" size="19" class="vue-feather group-hover-text-theme" />
        <list-icon v-if="source === 'th-list'" size="19" class="vue-feather group-hover-text-theme" />
        <info-icon
            v-if="source === 'info'"
            size="19"
            class="vue-feather group-hover-text-theme"
            :class="{ 'text-theme': isVisibleSidebar }"
        />
        <grid-icon v-if="source === 'th'" size="19" class="vue-feather group-hover-text-theme" />
        <link-icon v-if="source === 'share'" size="19" class="vue-feather group-hover-text-theme" />
        <x-icon v-if="source === 'close'" size="19" class="vue-feather group-hover-text-theme" />
        <search-icon v-if="source === 'search'" size="19" class="vue-feather group-hover-text-theme" />
        <cloud-off-icon v-if="source === 'shared-off'" size="19" class="vue-feather group-hover-text-theme" />
        <sorting-icon v-if="source === 'preview-sorting'" class="vue-feather group-hover-text-theme scale-125" />
        <CloudPlusIcon v-if="source === 'cloud-plus'" class="vue-feather group-hover-text-theme scale-125" />
    </button>
</template>

<script>
import SortingIcon from '../../Icons/SortingIcon'
import CloudPlusIcon from '../../Icons/CloudPlusIcon'
import {
    SearchIcon,
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
} from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'ToolbarButton',
    props: ['source', 'action'],
    computed: {
        ...mapGetters(['isVisibleSidebar']),
    },
    components: {
        SearchIcon,
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
}
</script>
