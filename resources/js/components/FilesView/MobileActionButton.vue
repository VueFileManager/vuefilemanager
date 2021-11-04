<template>
    <button class="mobile-action-button">
        <div class="flex">
            <hard-drive-icon v-if="icon === 'hard-drive'" size="15" class="icon dark-text-theme" />
            <upload-cloud-icon v-if="icon === 'upload-cloud'" size="15" class="icon dark-text-theme" />
            <link-icon v-if="icon === 'share'" size="15" class="icon dark-text-theme" />
            <trash2-icon v-if="icon === 'trash2'" size="15" class="icon dark-text-theme" />
            <users-icon v-if="icon === 'users'" size="15" class="icon dark-text-theme" />
            <user-check-icon v-if="icon === 'user-check'" size="15" class="icon dark-text-theme" />

			<search-icon v-if="icon === 'search'" size="15" class="icon dark-text-theme" />
            <refresh-cw-icon v-if="icon === 'refresh'" size="15" class="icon dark-text-theme" />
            <download-icon v-if="icon === 'download'" size="15" class="icon dark-text-theme" />
            <copy-icon v-if="icon === 'copy'" size="15" class="icon dark-text-theme" />
            <filter-icon v-if="icon === 'filter'" size="15" class="icon dark-text-theme" />
            <credit-card-icon v-if="icon === 'credit-card'" size="15" class="icon dark-text-theme" />
            <folder-plus-icon v-if="icon === 'folder-plus'" size="15" class="icon dark-text-theme" />
            <list-icon v-if="icon === 'th-list'" size="15" class="icon dark-text-theme" />
            <trash-icon v-if="icon === 'trash'" size="15" class="icon dark-text-theme" />
            <grid-icon v-if="icon === 'th'" size="15" class="icon dark-text-theme" />
            <user-plus-icon v-if="icon === 'user-plus'" size="15" class="icon dark-text-theme" />
            <plus-icon v-if="icon === 'plus'" size="15" class="icon dark-text-theme" />
            <check-square-icon v-if="icon === 'check-square'" size="15" class="icon dark-text-theme" />
            <x-square-icon v-if="icon === 'x-square'" size="15" class="icon dark-text-theme" />
            <check-icon v-if="icon === 'check'" size="15" class="icon dark-text-theme" />
            <dollar-sign-icon v-if="icon === 'dollar-sign'" size="15" class="icon dark-text-theme" />
            <sorting-icon v-if="icon === 'preview-sorting'" class="icon dark-text-theme preview-sorting" />
            <cloud-plus-icon v-if="icon === 'cloud-plus'" class="icon dark-text-theme preview-sorting" />
            <span v-if="$slots.default" class="label">
                <slot></slot>
            </span>
        </div>
    </button>
</template>

<script>
    import { UserCheckIcon, HardDriveIcon, UploadCloudIcon, LinkIcon, Trash2Icon, UsersIcon, SearchIcon, RefreshCwIcon, DownloadIcon, CopyIcon, FilterIcon, DollarSignIcon, CheckIcon, XSquareIcon, CheckSquareIcon, FolderPlusIcon, ListIcon, GridIcon, TrashIcon, UserPlusIcon, PlusIcon, CreditCardIcon  } from 'vue-feather-icons'
    import CloudPlusIcon from '/resources/js/components/FilesView/Icons/CloudPlusIcon'
    import SortingIcon from '/resources/js/components/FilesView/Icons/SortingIcon'

    export default {
        name: 'MobileActionButton',
        props: [
            'icon'
        ],
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
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .mobile-action-button {
        background: $light_background;
        margin-right: 8px;
        border-radius: 8px;
        padding: 7px 14px;
        cursor: pointer;
        border: none;
        @include transition(150ms);

        .flex {
            display: flex;
            align-items: center;
        }

        .icon {
            @include font-size(14);

            path, line, polyline, rect, circle, polygon {
                @include transition(150ms);
            }
        }

        .label {
            @include transition(150ms);
            @include font-size(14);
            font-weight: 700;
            color: $text;
			padding-left: 10px;
		}

        &:active {
            @include transform(scale(0.95));
        }
    }

    .dark {
        .mobile-action-button {
            background: $dark_mode_foreground;

            path, line, polyline, rect, circle, polygon {
                color: inherit;
            }

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
