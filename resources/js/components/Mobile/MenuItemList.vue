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
                <file-text-icon v-if="link.icon === 'file-text'" size="17"></file-text-icon>
                <database-icon v-if="link.icon === 'database'" size="17"></database-icon>
                <credit-card-icon v-if="link.icon === 'credit-card'" size="17"></credit-card-icon>
                <cloud-icon v-if="link.icon === 'cloud'" size="17"></cloud-icon>
                <monitor-icon v-if="link.icon === 'monitor'" size="17"></monitor-icon>
                <box-icon v-if="link.icon === 'box'" size="17"></box-icon>
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
        CreditCardIcon,
        HardDriveIcon,
        FileTextIcon,
        SettingsIcon,
        DatabaseIcon,
        MonitorIcon,
        Trash2Icon,
        CloudIcon,
        PowerIcon,
        ShareIcon,
        UsersIcon,
        UserIcon,
        LockIcon,
        BoxIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'MenuBar',
        components: {
            BoxIcon,
            MonitorIcon,
            ChevronRightIcon,
            UploadCloudIcon,
            CreditCardIcon,
            HardDriveIcon,
            DatabaseIcon,
            FileTextIcon,
            SettingsIcon,
            Trash2Icon,
            CloudIcon,
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

                path, line, polyline, rect, circle, ellipse {
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

            path, line, polyline, rect, circle, ellipse {
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

                path, line, polyline, rect, circle, ellipse {
                    stroke: $dark_mode_text_primary;
                }
            }

            .menu-link {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
