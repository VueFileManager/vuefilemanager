<template>
    <section id="viewport">

        <ContentSidebar v-if="false">

            <!--Locations-->
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
                </div>
            </ContentGroup>
        </ContentSidebar>

        <keep-alive :include="['Users']">
            <router-view/>
        </keep-alive>
    </section>
</template>

<script>
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import { mapGetters } from 'vuex'
    import { UsersIcon } from 'vue-feather-icons'

    export default {
        name: 'Settings',
        computed: {
            ...mapGetters(['config']),
        },
        components: {
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
