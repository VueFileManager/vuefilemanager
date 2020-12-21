<template>
    <button class="mobile-action-button">
        <div class="flex">
            <credit-card-icon v-if="icon === 'credit-card'" size="15" class="icon"></credit-card-icon>
            <folder-plus-icon v-if="icon === 'folder-plus'" size="15" class="icon"></folder-plus-icon>
            <list-icon v-if="icon === 'th-list'" size="15" class="icon"></list-icon>
            <trash-icon v-if="icon === 'trash'" size="15" class="icon"></trash-icon>
            <grid-icon v-if="icon === 'th'" size="15" class="icon"></grid-icon>
            <user-plus-icon v-if="icon === 'user-plus'" size="15" class="icon"></user-plus-icon>
            <plus-icon v-if="icon === 'plus'" size="15" class="icon"></plus-icon>
            <check-square-icon v-if="icon === 'check-square'" size="15" class="icon"></check-square-icon>
            <x-square-icon v-if="icon === 'x-square'" size="15" class="icon"></x-square-icon>
            <check-icon v-if="icon === 'check'" size="15" class="icon"></check-icon>
            <sorting-and-preview-icon v-if="icon === 'preview-sorting'" size="15" class="icon preview-sorting"></sorting-and-preview-icon>
            <span class="label">
                <slot></slot>
            </span>
        </div>
    </button>
</template>

<script>
    import { CheckIcon, XSquareIcon, CheckSquareIcon, FolderPlusIcon, ListIcon, GridIcon, TrashIcon, UserPlusIcon, PlusIcon, CreditCardIcon  } from 'vue-feather-icons'
    import SortingAndPreviewIcon from '@/components/FilesView/Icons/SortingAndPreviewIcon'

    export default {
        name: 'MobileActionButton',
        props: [
            'icon'
        ],
        components: {
            SortingAndPreviewIcon,
            CheckSquareIcon,
            CreditCardIcon,
            FolderPlusIcon,
            UserPlusIcon,
            XSquareIcon,
            CheckIcon,
            TrashIcon,
            PlusIcon,
            ListIcon,
            GridIcon,
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-action-button {
        background: $light_background;
        margin-right: 15px;
        border-radius: 8px;
        padding: 7px 10px;
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

            path, line, polyline, rect, circle {
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

        /*&:hover {
            background: rgba($theme, 0.1);

            .icon {
                path, line, polyline, rect, circle {
                    stroke: $theme;
                }
            }

            .label {
                color: $theme;
            }
        }*/
    }

    @media (prefers-color-scheme: dark) {
        .mobile-action-button {
            background: $dark_mode_foreground;

            path, line, polyline, rect, circle {
                stroke: $theme;
            }

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
