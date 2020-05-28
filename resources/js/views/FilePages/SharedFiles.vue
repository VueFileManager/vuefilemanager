<template>
    <section id="viewport">

        <ContentSidebar>

            <!--Navigator-->
            <ContentGroup :title="$t('sidebar.locations_title')">
                <div class="menu-list-wrapper vertical">
                    <li class="menu-list-item link" :class="{'is-active': $isThisLocation(['shared'])}" @click="getShared()">
                        <div class="icon">
                            <link-icon size="17"></link-icon>
                        </div>
                        <div class="label">
                            {{ $t('sidebar.my_shared') }}
                        </div>
                    </li>
                    <li class="menu-list-item link" :class="{'is-active': $isThisLocation(['participant_uploads'])}" @click="getParticipantUploads()">
                        <div class="icon">
                            <users-icon size="17"></users-icon>
                        </div>
                        <div class="label">
                            {{ $t('sidebar.participant_uploads') }}
                        </div>
                    </li>
                </div>
            </ContentGroup>

        </ContentSidebar>

        <ContentFileView />
    </section>
</template>

<script>
    import ContentFileView from '@/components/Others/ContentFileView'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import {
        LinkIcon,
        UsersIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'FilesView',
        components: {
            ContentFileView,
            ContentSidebar,
            ContentGroup,
            LinkIcon,
            UsersIcon,
        },
        methods: {
            getShared() {
                this.$store.dispatch('getShared', [{back: false, init: false}])
            },
            getParticipantUploads() {
                this.$store.dispatch('getParticipantUploads')
            },
        },
        mounted() {
            this.getShared()
        }
    }
</script>

<style lang="scss" scoped>
</style>
