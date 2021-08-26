<template>
    <div class="popup-header">
        <div class="icon">
            <corner-down-right-icon v-if="icon === 'move'" size="15" class="title-icon text-theme dark-text-theme" />
            <share-icon v-if="icon === 'share'" size="17" class="title-icon text-theme dark-text-theme" />
            <edit2-icon v-if="icon === 'edit'" size="17" class="title-icon text-theme dark-text-theme" />
            <key-icon v-if="icon === 'key'" size="17" class="title-icon text-theme dark-text-theme" />
            <users-icon v-if="icon === 'users'" size="17" class="title-icon text-theme dark-text-theme" />
            <user-plus-icon v-if="icon === 'user-plus'" size="17" class="title-icon text-theme dark-text-theme" />
        </div>
        <div class="label">
            <h1 class="title">{{ title }}</h1>
            <x-icon @click="closePopup" size="22" class="close-icon hover-text-theme" />
        </div>
    </div>
</template>

<script>
    import {KeyIcon, UserPlusIcon, CornerDownRightIcon, LinkIcon, XIcon, Edit2Icon, ShareIcon, UsersIcon} from 'vue-feather-icons'
    import {events} from '/resources/js/bus'

    export default {
        name: 'PopupHeader',
        props: [
            'title', 'icon'
        ],
        components: {
            CornerDownRightIcon,
			UserPlusIcon,
			UsersIcon,
            ShareIcon,
            Edit2Icon,
            LinkIcon,
			KeyIcon,
            XIcon,
        },
        methods: {
            closePopup() {
                events.$emit('popup:close')
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .popup-header {
        padding: 20px;
        display: flex;
        align-items: center;

        .icon {
            margin-right: 10px;
            line-height: 0;

            path, line, polyline, rect, circle {
                color: inherit;
            }
        }

        .title {
            @include font-size(17);
            font-weight: 700;
            color: $text;
        }

        .message {
            @include font-size(16);
            color: #8b8f9a;
            margin-top: 5px;
        }

        .label {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;

            .close-icon {
                padding: 1px 4px;
                border-radius: 6px;

                &:hover {
                    background: $light_background;

                    line {
                        color: inherit;
                    }
                }

                cursor: pointer;
            }
        }
    }

    @media only screen and (max-width: 690px) {
        .popup-header {
            padding: 15px;
        }
    }

    .dark-mode {
        .popup-header {
            .label {
                .close-icon {
                    &:hover {
                        background: $dark_mode_foreground;
                    }

                    cursor: pointer;
                }
            }

            .title {
                color: $dark_mode_text_primary;
            }

            .message {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
