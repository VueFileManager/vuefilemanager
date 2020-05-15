<template>
    <section id="viewport">

        <ContentSidebar>

            <!--User Headline-->
            <UserHeadline class="user-headline"/>

            <!--Locations-->
            <ContentGroup title="Menu" class="navigator">
                <div class="menu-list-wrapper">
                    <router-link :to="{name: 'Profile'}" class="menu-list-item link">
                        <div class="icon">
                            <user-icon size="17"></user-icon>
                        </div>
                        <div class="label">
                            Profile Settings
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Password'}" class="menu-list-item link">
                        <div class="icon">
                            <lock-icon size="17"></lock-icon>
                        </div>
                        <div class="label">
                            Password
                        </div>
                    </router-link>
                    <!--<router-link :to="{name: 'Profile'}" class="menu-list-item link">
                        <div class="icon">
                            <hard-drive-icon size="17"></hard-drive-icon>
                        </div>
                        <div class="label">
                            Storage
                        </div>
                    </router-link>-->
                </div>
            </ContentGroup>
        </ContentSidebar>

        <router-view/>
    </section>
</template>

<script>
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import UserHeadline from '@/components/Sidebar/UserHeadline'
    import {
        UserIcon,
        LockIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'Settings',
        components: {
            ContentSidebar,
            UserHeadline,
            ContentGroup,
            UserIcon,
            LockIcon,
        },
    }
</script>

<style lang="scss" scoped>

    .user-headline {
        margin-bottom: 38px;
    }
</style>
