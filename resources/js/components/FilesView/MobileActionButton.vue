<template>
    <button class="mobile-action-button">
        <div class="flex">
            <file-plus-icon v-if="icon === 'file-plus'" size="15" class="icon dark-text-theme" />
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
            <span class="label">
                <slot></slot>
            </span>
        </div>
    </button>
</template>

<script>
    import { FilePlusIcon, FilterIcon, DollarSignIcon, CheckIcon, XSquareIcon, CheckSquareIcon, FolderPlusIcon, ListIcon, GridIcon, TrashIcon, UserPlusIcon, PlusIcon, CreditCardIcon  } from 'vue-feather-icons'
    import SortingIcon from '@/components/FilesView/Icons/SortingIcon'

    export default {
        name: 'MobileActionButton',
        props: [
            'icon'
        ],
        components: {
			FilePlusIcon,
            SortingIcon,
            CheckSquareIcon,
            DollarSignIcon,
            CreditCardIcon,
            FolderPlusIcon,
            UserPlusIcon,
            XSquareIcon,
            FilterIcon,
            CheckIcon,
            TrashIcon,
            PlusIcon,
            ListIcon,
            GridIcon,
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

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
            margin-right: 10px;
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
        }

        &:active {
            @include transform(scale(0.95));
        }
    }

    @media (prefers-color-scheme: dark) {
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
