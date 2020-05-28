<template>
    <div id="single-page" v-if="app">
        <div id="page-content" class="medium-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <!--User thumbnail-->
                <div class="user-thumbnail">
                    <div class="avatar">
                        <UserImageInput
                                v-model="avatar"
                                :avatar="app.user.avatar"
                        />
                    </div>
                    <div class="info">
                        <b class="name">{{ app.user.name }}</b>
                        <span class="email">{{ app.user.email }}</span>
                    </div>
                </div>

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'Profile'}" class="menu-list-item link">
                        <div class="icon">
                            <user-icon size="17"></user-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.profile') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'Storage'}" class="menu-list-item link">
                        <div class="icon">
                            <hard-drive-icon size="17"></hard-drive-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.storage') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'Password'}" class="menu-list-item link">
                        <div class="icon">
                            <lock-icon size="17"></lock-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.password') }}
                        </div>
                    </router-link>

                    <!--<router-link replace :to="{name: 'UserDelete'}" v-if="user.attributes.name !== app.user.name" class="menu-list-item link">
                        <div class="icon">
                            <trash2-icon size="17"></trash2-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.delete') }}
                        </div>
                    </router-link>-->
                </div>

                <!--Router Content-->
                <router-view :user="app.user" />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import UserImageInput from '@/components/Others/UserImageInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'

    import { mapGetters } from 'vuex'
    import {
        HardDriveIcon,
        UserIcon,
        LockIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'Settings',
        components: {
            UserImageInput,
            MobileHeader,
            PageHeader,
            Spinner,
            HardDriveIcon,
            UserIcon,
            LockIcon,
        },
        computed: {
            ...mapGetters([
                'config', 'app'
            ]),
        },
        data() {
            return {
                avatar: undefined,
                isLoading: false,
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .user-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .avatar {
            margin-right: 20px;

            img {
                line-height: 0;
                width: 62px;
                height: 62px;
                border-radius: 12px;
            }
        }

        .info {

            .name {
                display: block;
                @include font-size(17);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(14);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>
