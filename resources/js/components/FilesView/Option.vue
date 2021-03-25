<template>
    <li class="menu-option group">
        <div class="icon group-hover-text-theme">
            <calendar-icon v-if="icon === 'calendar'" size="17" class="group-hover-text-theme"/>
            <grid-icon v-if="icon === 'grid'" size="17" class="group-hover-text-theme"/>
            <list-icon v-if="icon === 'list'" size="17" class="group-hover-text-theme"/>
            <trash-2-icon v-if="icon === 'trash'" size="17" class="group-hover-text-theme"/>
            <life-buoy-icon v-if="icon === 'restore'" size="17" class="group-hover-text-theme"/>
            <trash-icon v-if="icon === 'empty-trash'" size="17" class="group-hover-text-theme"/>
            <eye-icon v-if="icon ==='detail'" size="17" class="group-hover-text-theme"/>
            <download-cloud-icon v-if="icon === 'download'" size="17" class="group-hover-text-theme"/>
            <edit2-icon v-if="icon === 'rename'" size="17" class="group-hover-text-theme"/>
            <corner-down-right-icon v-if="icon === 'move-item'" size="17" class="group-hover-text-theme"/>
            <link-icon v-if="icon === 'share'" size="17" class="group-hover-text-theme"/>
            <star-icon v-if="icon === 'favourites'" size="17" class="group-hover-text-theme"/>
            <folder-plus-icon v-if="icon === 'create-folder'" size="17" class="group-hover-text-theme"/>
            <smile-icon v-if="icon === 'no-options'" size="17" class="group-hover-text-theme"/>
            <paperclip-icon v-if="icon === 'zip-folder'" size="17" class="group-hover-text-theme"/>
            <alphabet-icon v-if="icon === 'alphabet'" size="17" class="group-hover-text-theme"/>
        </div>
        <div class="text-label group-hover-text-theme">
            {{ title }}
        </div>
    </li>
</template>

<script>
import AlphabetIcon from '@/components/FilesView/Icons/AlphabetIcon'
import {
    CornerDownRightIcon,
    DownloadCloudIcon,
    FolderPlusIcon,
    PaperclipIcon,
    LifeBuoyIcon,
    Trash2Icon,
    Edit2Icon,
    TrashIcon,
    StarIcon,
    LinkIcon,
    EyeIcon,
    SmileIcon,
    GridIcon,
    ListIcon,
    CalendarIcon,
} from 'vue-feather-icons'

    export default {
        name: 'Option',
        props:['title' , 'icon'],
        components: {
            CornerDownRightIcon,
            DownloadCloudIcon,
            FolderPlusIcon,
            PaperclipIcon,
            LifeBuoyIcon,
            AlphabetIcon,
            Trash2Icon,
            SmileIcon,
            Edit2Icon,
            TrashIcon,
            LinkIcon,
            StarIcon,
            EyeIcon,
            GridIcon,
            ListIcon,
            CalendarIcon,
        }
    }
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

.menu-option {
    white-space: nowrap;
    font-weight: 700;
    @include font-size(14);
    padding: 15px 20px;
    cursor: pointer;
    width: 100%;
    color: $text;
    display: flex;
    align-items: center;

    .icon {
        margin-right: 20px;
        line-height: 0;

        path,
        line,
        polyline,
        rect,
        circle,
        polygon {
            color: inherit;
        }
    }

    .text-label {
        @include font-size(16);
    }

    &:hover {
        background: $light_background;
    }
}
@media (prefers-color-scheme: dark) {
    .danger {
        &:hover {
            background: rgba($danger, 0.1) !important;
        }
    }
    .menu-option {
        color: $dark_mode_text_primary;

        &:hover {
            background: rgba($theme, 0.1);
        }
    } 
}

</style>
