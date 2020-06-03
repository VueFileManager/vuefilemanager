<template>
    <section id="viewport">

        <ContentSidebar>
            <!--SaaS-->
            <ContentGroup title="SaaS" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Plans'}" class="menu-list-item link">
                        <div class="icon">
                            <database-icon size="17"></database-icon>
                        </div>
                        <div class="label">
                            Plans
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Gateways'}" class="menu-list-item link">
                        <div class="icon">
                            <credit-card-icon size="17"></credit-card-icon>
                        </div>
                        <div class="label">
                            Payment Methods
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Invoices'}" class="menu-list-item link">
                        <div class="icon">
                            <file-text-icon size="17"></file-text-icon>
                        </div>
                        <div class="label">
                            Invoices
                        </div>
                    </router-link>
                </div>
            </ContentGroup>

            <!--Admin-->
            <ContentGroup :title="$t('admin_menu.admin_label')" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Users'}" class="menu-list-item link">
                        <div class="icon">
                            <users-icon size="17"></users-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_menu.users') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'User'}" class="menu-list-item link">
                        <div class="icon">
                            <settings-icon size="17"></settings-icon>
                        </div>
                        <div class="label">
                            Settings
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
        </ContentSidebar>

        <router-view/>
    </section>
</template>

<script>
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import { mapGetters } from 'vuex'
    import { UsersIcon, SettingsIcon, FileTextIcon, CreditCardIcon, DatabaseIcon } from 'vue-feather-icons'

    export default {
        name: 'Settings',
        computed: {
            ...mapGetters(['config']),
        },
        components: {
            DatabaseIcon,
            CreditCardIcon,
            FileTextIcon,
            SettingsIcon,
            ContentSidebar,
            ContentGroup,
            UsersIcon,
        },
    }
</script>

<style lang="scss" scoped>

    .user-headline {
        margin-bottom: 38px;
    }
</style>
