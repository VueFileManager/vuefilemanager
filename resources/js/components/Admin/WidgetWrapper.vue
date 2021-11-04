<template>
    <div class="widget">
        <div class="widget-content">
            <div class="headline">
                <div class="icon">
                    <users-icon v-if="icon === 'users'" size="19" class="text-theme dark-text-theme"/>
                    <star-icon v-if="icon === 'star'" size="19" class="text-theme dark-text-theme"/>
                    <hard-drive-icon v-if="icon === 'hard-drive'" size="19" class="text-theme dark-text-theme"/>
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .widget-content {
        @include widget-card;
    }

    .headline {
        display: flex;

        .icon {
            margin-right: 10px;

            path, circle, line, polygon {
                color: inherit;
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

    .dark {
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
