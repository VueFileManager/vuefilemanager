<template>
    <li class="menu-option" :class="[icon === 'trash' ? 'danger' : '']">
        <div class="icon">
            <trash-2-icon v-if="icon === 'trash'" size="17"></trash-2-icon>
            <life-buoy-icon v-if="icon === 'restore'" size="17"></life-buoy-icon>
            <trash-icon v-if="icon === 'empty-trash'" size="17"></trash-icon>
            <eye-icon v-if="icon ==='detail'" size="17"></eye-icon>
            <download-cloud-icon v-if="icon === 'download'" size="17"></download-cloud-icon>
            <edit2-icon v-if="icon === 'rename'" size="17"></edit2-icon>
            <corner-down-right-icon v-if="icon === 'move-item'" size="17"></corner-down-right-icon>
            <link-icon v-if="icon === 'share'" size="17"></link-icon>
            <star-icon v-if="icon === 'favourites'" size="17"></star-icon>
            <folder-plus-icon v-if="icon === 'create-folder'" size="17"></folder-plus-icon>
            <smile-icon v-if="icon === 'no-options'" size="17"></smile-icon>
            <paperclip-icon v-if="icon === 'zip-folder'" size="17"></paperclip-icon> 
        </div>
        <div class="text-label">
            {{ title }}
        </div>
    </li>
</template>

<script>
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
    SmileIcon
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
            Trash2Icon,
            SmileIcon,
            Edit2Icon,
            TrashIcon,
            LinkIcon,
            StarIcon,
            EyeIcon,
        }
    }
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.danger {
    .text-label {
        color: $danger !important;
    }
    .icon {
        path,
        line,
        polyline,
        rect,
        circle,
        polygon {
            stroke: $danger !important;
        }
    }
}

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
    }

    .text-label {
        @include font-size(16);
    }

    &:hover {
        background: $light_background;

        .text-label {
            color: $theme;
        }

        path,
        line,
        polyline,
        rect,
        circle,
        polygon {
            stroke: $theme;
        }
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
