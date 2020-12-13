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
            <svg v-if="icon === 'preview-sorting'" size="15" class="icon preview-sorting"
                width="15px" height="15px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="VueFileManager" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                    <g id="Storage-Alert-Copy" transform="translate(-1092.000000, -28.000000)" stroke="#000000" stroke-width="1.4">
                        <g id="Toolbar" transform="translate(331.000000, 19.000000)">
                            <g id="Tools" transform="translate(581.000000, 9.000000)">
                                <g id="sort-icon" transform="translate(181.000000, 1.000000)">
                                    <rect id="Rectangle" x="9.77777778" y="0" width="6.22222222" height="6.22222222"></rect>
                                    <rect id="Rectangle" x="9.77777778" y="9.77777778" width="6.22222222" height="6.22222222"></rect>
                                    <line x1="0" y1="2" x2="6" y2="2" id="Path"></line>
                                    <line x1="0" y1="8" x2="6" y2="8" id="Path"></line>
                                    <line x1="0" y1="14" x2="6" y2="14" id="Path"></line>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
            <span class="label">
                <slot></slot>
            </span>
        </div>
    </button>
</template>

<script>
    import { FolderPlusIcon, ListIcon, GridIcon, TrashIcon, UserPlusIcon, PlusIcon, CreditCardIcon } from 'vue-feather-icons'

    export default {
        name: 'MobileActionButton',
        props: [
            'icon'
        ],
        components: {
            CreditCardIcon,
            FolderPlusIcon,
            UserPlusIcon,
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

        &:hover {
            background: rgba($theme, 0.1);

            .icon {
                path, line, polyline, rect, circle {
                    stroke: $theme;
                }
            }

            .label {
                color: $theme;
            }
        }
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
