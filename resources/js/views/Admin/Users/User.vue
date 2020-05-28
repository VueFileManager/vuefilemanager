<template>
    <div id="single-page" v-if="app">
        <div id="page-content" class="medium-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <!--User thumbnail-->
                <div class="user-thumbnail">
                    <div class="avatar">
                        <img :src="user.attributes.avatar" :alt="user.attributes.name">
                    </div>
                    <div class="info">
                        <b class="name">
                            {{ user.attributes.name }}
                            <ColorLabel color="purple">
                                {{ user.attributes.role }}
                            </ColorLabel></b>
                        <span class="email">{{ user.attributes.email }}</span>
                    </div>
                </div>

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'UserDetail'}" class="menu-list-item link">
                        <div class="icon">
                            <user-icon size="17"></user-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.detail') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'UserStorage'}" class="menu-list-item link">
                        <div class="icon">
                            <hard-drive-icon size="17"></hard-drive-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.storage') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'UserPassword'}" class="menu-list-item link">
                        <div class="icon">
                            <lock-icon size="17"></lock-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.password') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'UserDelete'}" v-if="user.attributes.name !== app.user.name" class="menu-list-item link">
                        <div class="icon">
                            <trash2-icon size="17"></trash2-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.delete') }}
                        </div>
                    </router-link>
                </div>

                <!--Router Content-->
                <router-view :user="user" @reload-user="fetchUser" />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import { UserIcon, HardDriveIcon, LockIcon, Trash2Icon } from 'vue-feather-icons'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            Trash2Icon,
            LockIcon,
            HardDriveIcon,
            StorageItemDetail,
            SectionTitle,
            MobileHeader,
            PageHeader,
            ColorLabel,
            UserIcon,
            Spinner,
        },
        computed: {
            ...mapGetters(['app']),
        },
        data() {
            return {
                isLoading: true,
                user: undefined,
            }
        },
        methods: {
            fetchUser() {
                axios.get('/api/users/' + this.$route.params.id + '/detail')
                    .then(response => {
                        this.user = response.data.data
                        this.isLoading = false
                    })
            }
        },
        created() {
            this.fetchUser()
        }
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

    @media only screen and (max-width: 960px) {

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
