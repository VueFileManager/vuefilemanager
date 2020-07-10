<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>
            <div class="content-page">

                <!--User thumbnail-->
                <div class="user-thumbnail">
                    <div class="avatar">
                        <img :src="user.data.attributes.avatar" :alt="user.data.attributes.name">
                        <!--<img :src="user.data.attributes.avatar" :alt="user.data.attributes.name" class="blurred">-->
                    </div>
                    <div class="info">
                        <b class="name">
                            {{ user.data.attributes.name }}
                            <ColorLabel color="purple">
                                {{ user.data.attributes.role }}
                            </ColorLabel>
                        </b>
                        <span class="email">{{ user.data.attributes.email }}</span>
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

                    <router-link v-if="config.isSaaS" replace :to="{name: 'UserSubscription'}" class="menu-list-item link">
                        <div class="icon">
                            <credit-card-icon size="17"></credit-card-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.subscription') }}
                        </div>
                    </router-link>

                    <router-link v-if="config.isSaaS" replace :to="{name: 'UserInvoices'}" class="menu-list-item link">
                        <div class="icon">
                            <file-text-icon size="17"></file-text-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.invoices') }}
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

                    <router-link replace :to="{name: 'UserDelete'}" v-if="user.data.attributes.name !== admin.name"
                                 class="menu-list-item link">
                        <div class="icon">
                            <trash2-icon size="17"></trash2-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_user.tabs.delete') }}
                        </div>
                    </router-link>
                </div>

                <!--Router Content-->
                <router-view :user="user" @reload-user="fetchUser"/>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {UserIcon, HardDriveIcon, LockIcon, Trash2Icon, FileTextIcon, CreditCardIcon} from 'vue-feather-icons'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            CreditCardIcon,
            HardDriveIcon,
            StorageItemDetail,
            SectionTitle,
            FileTextIcon,
            MobileHeader,
            PageHeader,
            ColorLabel,
            Trash2Icon,
            UserIcon,
            LockIcon,
            Spinner,
        },
        computed: {
            admin() {
                return this.$store.getters.user ? this.$store.getters.user.data.attributes : undefined
            },
            ...mapGetters(['config']),
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
                        this.user = response.data
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
        padding-bottom: 10px;
        padding-top: 15px;

        .avatar {
            margin-right: 20px;
            position: relative;

            img {
                line-height: 0;
                width: 62px;
                height: 62px;
                border-radius: 12px;
                z-index: 1;
                position: relative;

                &.blurred {
                    @include blurred-image;
                    top: 0;
                }
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
