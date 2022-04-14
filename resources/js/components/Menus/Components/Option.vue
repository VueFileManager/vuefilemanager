<template>
    <li
        class="flex items-center justify-between lg:py-3.5 py-4 px-5"
        :class="{ 'group cursor-pointer hover:bg-light-background dark:hover:bg-4x-dark-foreground': !isHoverDisabled }"
    >
        <div class="flex items-center">
            <div class="mr-4">
                <calendar-icon
                    v-if="icon === 'calendar'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <grid-icon
                    v-if="icon === 'grid'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <list-icon
                    v-if="icon === 'list'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <trash-2-icon
                    v-if="icon === 'trash'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <life-buoy-icon
                    v-if="icon === 'restore'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <trash-icon
                    v-if="icon === 'empty-trash'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <eye-icon
                    v-if="icon === 'detail'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <download-cloud-icon
                    v-if="icon === 'download'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <edit2-icon
                    v-if="icon === 'rename'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <corner-down-right-icon
                    v-if="icon === 'move-item'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <link-icon
                    v-if="icon === 'share'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <star-icon
                    v-if="icon === 'favourites'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <folder-plus-icon
                    v-if="icon === 'create-folder'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <smile-icon
                    v-if="icon === 'no-options'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <paperclip-icon
                    v-if="icon === 'zip-folder'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <alphabet-icon
                    v-if="icon === 'alphabet'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <star-icon
                    v-if="icon === 'star'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <hard-drive-icon
                    v-if="icon === 'hard-drive'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <upload-cloud-icon
                    v-if="icon === 'upload-cloud'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <link2-icon
                    v-if="icon === 'remote-upload'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <users-icon
                    v-if="icon === 'users'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <user-icon
                    v-if="icon === 'user'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <user-plus-icon
                    v-if="icon === 'user-plus'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <user-minus-icon
                    v-if="icon === 'user-minus'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <user-check-icon
                    v-if="icon === 'user-check'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <settings-icon
                    v-if="icon === 'settings'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <power-icon
                    v-if="icon === 'power'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <lock-icon
                    v-if="icon === 'lock'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <cloud-icon
                    v-if="icon === 'cloud'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <credit-card-icon
                    v-if="icon === 'credit-card'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <file-text-icon
                    v-if="icon === 'file-text'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <database-icon
                    v-if="icon === 'database'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <globe-icon
                    v-if="icon === 'globe'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <monitor-icon
                    v-if="icon === 'monitor'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <box-icon
                    v-if="icon === 'box'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
                <folder-plus-icon
                    v-if="icon === 'folder-plus'"
                    size="17"
                    class="vue-feather group-hover-text-theme"
                    :class="{ 'text-theme': isActive }"
                />
            </div>
            <b class="group-hover-text-theme text-sm font-bold" :class="{ 'text-theme': isActive }">
                {{ title }}
            </b>
        </div>
        <div v-if="arrow" class="ml-2">
            <chevron-right-icon
                v-if="arrow === 'right'"
                size="14"
                class="vue-feather group-hover-text-theme opacity-50"
                :class="{ 'text-theme': isActive }"
            />
            <arrow-up-icon
                v-if="arrow === 'up'"
                size="14"
                class="vue-feather group-hover-text-theme opacity-50"
                :class="{ 'text-theme': isActive }"
            />
            <arrow-down-icon
                v-if="arrow === 'down'"
                size="14"
                class="vue-feather group-hover-text-theme opacity-50"
                :class="{ 'text-theme': isActive }"
            />
        </div>
    </li>
</template>

<script>
import AlphabetIcon from '../../Icons/AlphabetIcon'
import {
	Link2Icon,
    UserMinusIcon,
    UserCheckIcon,
    UserPlusIcon,
    ArrowUpIcon,
    ArrowDownIcon,
    ChevronRightIcon,
    BoxIcon,
    MonitorIcon,
    GlobeIcon,
    FileTextIcon,
    CreditCardIcon,
    CloudIcon,
    LockIcon,
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
    DatabaseIcon,
} from 'vue-feather-icons'

export default {
    name: 'Option',
    props: ['isHoverDisabled', 'isActive', 'title', 'arrow', 'icon'],
    components: {
		Link2Icon,
        UserMinusIcon,
        UserCheckIcon,
        UserPlusIcon,
        ArrowUpIcon,
        ArrowDownIcon,
        BoxIcon,
        MonitorIcon,
        GlobeIcon,
        DatabaseIcon,
        ChevronRightIcon,
        FileTextIcon,
        CreditCardIcon,
        CloudIcon,
        LockIcon,
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
    },
}
</script>
