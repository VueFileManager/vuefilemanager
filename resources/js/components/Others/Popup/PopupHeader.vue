<template>
    <div class="popup-header">
        <div class="icon">
            <corner-down-right-icon v-if="icon === 'move'" size="15" class="title-icon"></corner-down-right-icon>
            <link-icon v-if="icon === 'share'" size="17" class="title-icon"></link-icon>
        </div>
        <div class="label">
            <h1 class="title">{{ title }}</h1>
            <x-icon @click="closePopup" size="22" class="close-icon"></x-icon>
        </div>
    </div>
</template>

<script>
    import { CornerDownRightIcon, LinkIcon, XIcon } from 'vue-feather-icons'
    import {events} from '@/bus'

    export default {
        name: 'PopupHeader',
        props: [
            'title', 'icon'
        ],
        components: {
            CornerDownRightIcon,
            LinkIcon,
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
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .popup-header {
        padding: 20px;
        display: flex;
        align-items: center;

        .icon {
            margin-right: 10px;
            line-height: 0;

            path, line, polyline, rect, circle {
                stroke: $theme;
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
                        stroke: $theme;
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

    @media (prefers-color-scheme: dark) {
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
