<template>
    <div class="widget">
        <div class="widget-content">
            <div class="headline">
                <div class="icon">
                    <users-icon v-if="icon === 'users'" size="19"></users-icon>
                    <star-icon v-if="icon === 'star'" size="19"></star-icon>
                    <hard-drive-icon v-if="icon === 'hard-drive'" size="19"></hard-drive-icon>
                </div>
                <b class="title">{{ title }}</b>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import { UsersIcon, StarIcon, HardDriveIcon, ChevronRightIcon } from 'vue-feather-icons'

    export default {
        name: 'WidgetWrapper',
        props: ['icon', 'title'],
        components: {
            ChevronRightIcon,
            HardDriveIcon,
            StarIcon,
            UsersIcon
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .widget-content {
        @include widget-card;
    }

    .headline {
        display: flex;

        .icon {
            margin-right: 10px;

            path, circle, line, polygon {
                stroke: $theme;
            }
        }
    }

    @media only screen and (max-width: 1190px) {
        .headline {

            .title {
                @include font-size(14);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .widget-content {
            background: $dark_mode_foreground;
        }

        .headline {
            .title {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
