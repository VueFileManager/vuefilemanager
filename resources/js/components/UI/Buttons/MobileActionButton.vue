<template>
    <button class="mr-2 inline-block rounded-xl bg-light-background py-2 px-3.5 dark:bg-2x-dark-foreground">
        <div class="flex items-center">
            <hard-drive-icon v-if="icon === 'hard-drive'" size="15" class="vue-feather dark-text-theme" />
            <upload-cloud-icon v-if="icon === 'upload-cloud'" size="15" class="vue-feather dark-text-theme" />
            <link-icon v-if="icon === 'share'" size="15" class="vue-feather dark-text-theme" />
            <trash2-icon v-if="icon === 'trash2'" size="15" class="vue-feather dark-text-theme" />
            <users-icon v-if="icon === 'users'" size="15" class="vue-feather dark-text-theme" />
            <user-check-icon v-if="icon === 'user-check'" size="15" class="vue-feather dark-text-theme" />
            <search-icon v-if="icon === 'search'" size="15" class="vue-feather dark-text-theme" />
            <refresh-cw-icon v-if="icon === 'refresh'" size="15" class="vue-feather dark-text-theme" />
            <download-icon v-if="icon === 'download'" size="15" class="vue-feather dark-text-theme" />
            <copy-icon v-if="icon === 'copy'" size="15" class="vue-feather dark-text-theme" />
            <filter-icon v-if="icon === 'filter'" size="15" class="vue-feather dark-text-theme" />
            <credit-card-icon v-if="icon === 'credit-card'" size="15" class="vue-feather dark-text-theme" />
            <folder-plus-icon v-if="icon === 'folder-plus'" size="15" class="vue-feather dark-text-theme" />
            <list-icon v-if="icon === 'th-list'" size="15" class="vue-feather dark-text-theme" />
            <trash-icon v-if="icon === 'trash'" size="15" class="vue-feather dark-text-theme" />
            <grid-icon v-if="icon === 'th'" size="15" class="vue-feather dark-text-theme" />
            <user-plus-icon v-if="icon === 'user-plus'" size="15" class="vue-feather dark-text-theme" />
            <plus-icon v-if="icon === 'plus'" size="15" class="vue-feather dark-text-theme" />
            <check-square-icon v-if="icon === 'check-square'" size="15" class="vue-feather dark-text-theme" />
            <x-square-icon v-if="icon === 'x-square'" size="15" class="vue-feather dark-text-theme" />
            <check-icon v-if="icon === 'check'" size="15" class="vue-feather dark-text-theme" />
            <dollar-sign-icon v-if="icon === 'dollar-sign'" size="15" class="vue-feather dark-text-theme" />
            <sorting-icon v-if="icon === 'preview-sorting'" class="vue-feather dark-text-theme preview-sorting" />
            <cloud-plus-icon v-if="icon === 'cloud-plus'" class="vue-feather dark-text-theme preview-sorting" />

            <span v-if="$slots.default" class="ml-2 text-sm font-bold">
                <slot />
            </span>
        </div>
    </button>
</template>

<script>
import {
    UserCheckIcon,
    HardDriveIcon,
    UploadCloudIcon,
    LinkIcon,
    Trash2Icon,
    UsersIcon,
    SearchIcon,
    RefreshCwIcon,
    DownloadIcon,
    CopyIcon,
    FilterIcon,
    DollarSignIcon,
    CheckIcon,
    XSquareIcon,
    CheckSquareIcon,
    FolderPlusIcon,
    ListIcon,
    GridIcon,
    TrashIcon,
    UserPlusIcon,
    PlusIcon,
    CreditCardIcon,
} from 'vue-feather-icons'
import CloudPlusIcon from '../../Icons/CloudPlusIcon'
import SortingIcon from '../../Icons/SortingIcon'

export default {
    name: 'MobileActionButton',
    props: ['icon'],
    components: {
        UserCheckIcon,
        HardDriveIcon,
        UploadCloudIcon,
        LinkIcon,
        Trash2Icon,
        UsersIcon,
        CheckSquareIcon,
        DollarSignIcon,
        CreditCardIcon,
        FolderPlusIcon,
        RefreshCwIcon,
        CloudPlusIcon,
        UserPlusIcon,
        DownloadIcon,
        SortingIcon,
        XSquareIcon,
        FilterIcon,
        SearchIcon,
        CheckIcon,
        TrashIcon,
        PlusIcon,
        CopyIcon,
        ListIcon,
        GridIcon,
    },
}
</script>
