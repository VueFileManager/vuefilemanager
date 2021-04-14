<template>
    <li class="menu-option group">
        <div class="icon group-hover-text-theme">
            <calendar-icon v-if="icon === 'calendar'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <grid-icon v-if="icon === 'grid'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <list-icon v-if="icon === 'list'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <trash-2-icon v-if="icon === 'trash'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <life-buoy-icon v-if="icon === 'restore'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <trash-icon v-if="icon === 'empty-trash'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <eye-icon v-if="icon ==='detail'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <download-cloud-icon v-if="icon === 'download'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <edit2-icon v-if="icon === 'rename'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <corner-down-right-icon v-if="icon === 'move-item'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <link-icon v-if="icon === 'share'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <star-icon v-if="icon === 'favourites'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <folder-plus-icon v-if="icon === 'create-folder'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <smile-icon v-if="icon === 'no-options'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <paperclip-icon v-if="icon === 'zip-folder'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <alphabet-icon v-if="icon === 'alphabet'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <star-icon v-if="icon === 'star'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <hard-drive-icon v-if="icon === 'hard-drive'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <upload-cloud-icon v-if="icon === 'upload-cloud'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <users-icon v-if="icon === 'users'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <user-icon v-if="icon === 'user'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <settings-icon v-if="icon === 'settings'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
            <power-icon v-if="icon === 'power'" size="17" class="group-hover-text-theme" :class="{'text-theme': isActive}"/>
        </div>
        <div class="text-label group-hover-text-theme" :class="{'text-theme': isActive}">
            {{ title }}
        </div>
    </li>
</template>

<script>
import AlphabetIcon from '@/components/FilesView/Icons/AlphabetIcon'
import {
    PowerIcon,
    SettingsIcon,
    UsersIcon,
    UserIcon,
    UploadCloudIcon,
    HardDriveIcon,
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
        props:[
            'isActive',
            'title',
            'icon'
        ],
        components: {
            CornerDownRightIcon,
            DownloadCloudIcon,
            UploadCloudIcon,
            FolderPlusIcon,
            HardDriveIcon,
            PaperclipIcon,
            SettingsIcon,
            LifeBuoyIcon,
            CalendarIcon,
            AlphabetIcon,
            Trash2Icon,
            SmileIcon,
            PowerIcon,
            UsersIcon,
            Edit2Icon,
            TrashIcon,
            LinkIcon,
            StarIcon,
            GridIcon,
            ListIcon,
            UserIcon,
            EyeIcon,
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

    .menu-option {
        color: $dark_mode_text_primary;

        &:hover {
            background: lighten($dark_mode_foreground, 2%);
        }
    } 
}

</style>
