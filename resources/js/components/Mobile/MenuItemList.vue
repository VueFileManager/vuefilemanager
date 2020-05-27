<template>
    <ul class="link-group">
        <router-link :to="{name: link.routeName}" v-for="(link, i) in navigation" :key="i" v-if="link.isVisible" :class="link.icon" class="link-item" @click.native="$emit('menu', link.icon)">
            <div class="menu-icon">
                <hard-drive-icon v-if="link.icon === 'hard-drive'" size="17"></hard-drive-icon>
                <share-icon v-if="link.icon === 'share'" size="17"></share-icon>
                <trash2-icon v-if="link.icon === 'trash'" size="17"></trash2-icon>
                <power-icon v-if="link.icon === 'power'" size="17"></power-icon>
                <settings-icon v-if="link.icon === 'settings'" size="17"></settings-icon>
                <upload-cloud-icon v-if="link.icon === 'latest'" size="17"></upload-cloud-icon>
                <user-icon v-if="link.icon === 'user'" size="17"></user-icon>
                <users-icon v-if="link.icon === 'users'" size="17"></users-icon>
                <lock-icon v-if="link.icon === 'lock'" size="17"></lock-icon>
            </div>
            <b class="menu-link">
                <span>{{ link.title }}</span>
                <chevron-right-icon size="15" class="arrow-right"></chevron-right-icon>
            </b>
        </router-link>
    </ul>
</template>

<script>
    import {
        ChevronRightIcon,
        UploadCloudIcon,
        HardDriveIcon,
        SettingsIcon,
        Trash2Icon,
        PowerIcon,
        ShareIcon,
        UsersIcon,
        UserIcon,
        LockIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'MenuBar',
        components: {
            ChevronRightIcon,
            UploadCloudIcon,
            HardDriveIcon,
            SettingsIcon,
            Trash2Icon,
            PowerIcon,
            UsersIcon,
            ShareIcon,
            LockIcon,
            UserIcon,
        },
        props: [
            'navigation'
        ],
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .link-item {
        display: flex;
        text-decoration: none;
        padding: 17px 0;
        width: 100%;

        &.power {

            .menu-icon {

                path, line, polyline, rect, circle {
                    stroke: $red;
                }
            }

            .menu-link {
                color: $red;
            }
        }

        .menu-icon {
            display: block;
            margin-right: 20px;

            svg {
                margin-top: -1px;
                vertical-align: middle;
            }

            path, line, polyline, rect, circle {
                stroke: $text;
            }
        }

        .menu-link {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: $text;

            span {
                @include font-size(14);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .link-item {

            .menu-icon {

                path, line, polyline, rect, circle {
                    stroke: $dark_mode_text_primary;
                }
            }

            .menu-link {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
