<template>
    <section id="viewport">

        <!--Navigation Sidebar-->
        <MenuBar />

        <ContentSidebar>

            <!--Admin-->
            <ContentGroup :title="$t('global.admin')" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Dashboard'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <box-icon size="17"></box-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.dashboard') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Users'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <users-icon size="17"></users-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.users') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'AppOthers'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <settings-icon size="17"></settings-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.settings') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Pages'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <monitor-icon size="17"></monitor-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.pages') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Language'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <globe-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.languages') }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>

            <!--SaaS-->
            <ContentGroup v-if="config.isSaaS" title="Subscription" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Plans'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <database-icon size="17"></database-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.plans') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Invoices'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <file-text-icon size="17"></file-text-icon>
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.invoices') }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
        </ContentSidebar>

        <router-view/>
    </section>
</template>

<script>
    import { UsersIcon, SettingsIcon, FileTextIcon, CreditCardIcon, DatabaseIcon, BoxIcon, MonitorIcon, GlobeIcon } from 'vue-feather-icons'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import MenuBar from '@/components/Sidebar/MenuBar'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Settings',
        computed: {
            ...mapGetters(['config']),
        },
        components: {
            ContentSidebar,
            CreditCardIcon,
            FileTextIcon,
            ContentGroup,
            DatabaseIcon,
            SettingsIcon,
            MonitorIcon,
            UsersIcon,
            MenuBar,
            BoxIcon,
            GlobeIcon
        },
    }
</script>

<style lang="scss" scoped>

    .user-headline {
        margin-bottom: 38px;
    }
</style>
