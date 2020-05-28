<template>
    <section id="viewport">
        <ContentSidebar>

            <!--Tools-->
            <ContentGroup :title="$t('sidebar.tools_title')" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <div class="menu-list-item link" @click="emptyTrash()">
                        <div class="icon">
                            <trash-icon size="17"></trash-icon>
                        </div>
                        <div class="label">
                            {{ $t('context_menu.empty_trash') }}
                        </div>
                    </div>
                </div>
            </ContentGroup>
        </ContentSidebar>
        <ContentFileView/>
    </section>
</template>

<script>
    import ContentFileView from '@/components/Others/ContentFileView'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import {
        TrashIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'FilesView',
        components: {
            ContentFileView,
            ContentSidebar,
            ContentGroup,
            TrashIcon,
        },
        methods: {
            emptyTrash() {
                this.$store.dispatch('emptyTrash')
            },
        },
        created() {
            this.$store.dispatch('getTrash')
        }
    }
</script>

<style lang="scss" scoped>
</style>
