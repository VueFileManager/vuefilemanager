<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$t($router.currentRoute.meta.title)"/>
            <PageHeader :can-back="true" :title="$t($router.currentRoute.meta.title)"/>

            <div class="content-page">

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'AppOthers'}"
                                 class="menu-list-item link border-bottom-theme">
                        <div class="icon text-theme">
                            <settings-icon size="17" />
                        </div>
                        <div class="label text-theme ">
                            {{ $t('admin_settings.tabs.others') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'AppAppearance'}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <eye-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_settings.tabs.appearance') }}
                        </div>
                    </router-link>

                    <router-link v-if="config.isSaaS" replace :to="{name: 'AppBillings'}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <file-text-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_settings.tabs.billings') }}
                        </div>
                    </router-link>

                    <router-link v-if="config.isSaaS" replace :to="{name: 'AppPayments'}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <credit-card-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_settings.tabs.payments') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'AppIndex'}" class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <home-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            Homepage
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'AppEmail'}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <mail-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_settings.tabs.email') }}
                        </div>
                    </router-link>
                </div>

                <!--Router Content-->
                <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
    import {UsersIcon, SettingsIcon, Trash2Icon, EyeIcon, FileTextIcon, CodeIcon, MailIcon, CreditCardIcon, HomeIcon} from 'vue-feather-icons'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import {mapGetters} from 'vuex'

    export default {
        name: 'AppSettings',
        components: {
            HomeIcon,
            CreditCardIcon,
            CodeIcon,
            MailIcon,
            FileTextIcon,
            EyeIcon,
            UsersIcon,
            Trash2Icon,
            SettingsIcon,
            SectionTitle,
            MobileHeader,
            PageHeader,
        },
        computed: {
            ...mapGetters(['config']),
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
</style>
