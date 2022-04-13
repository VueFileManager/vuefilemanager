<template>
    <div
        class="sticky top-0 z-[19] block flex w-full items-center justify-between bg-white py-5 px-4 text-center dark:bg-dark-background lg:hidden"
    >
        <NavigationBar />
    </div>
</template>

<script>
import TeamMembersPreview from '../../Teams/Components/TeamMembersPreview'
import TeamMembersButton from '../../Teams/Components/TeamMembersButton'
import { MenuIcon } from 'vue-feather-icons'
import NavigationBar from './NavigationBar'

export default {
    name: 'MobileUploadRequestToolBar',
    components: {
        NavigationBar,
        TeamMembersPreview,
        TeamMembersButton,
        MenuIcon,
    },
    methods: {
        showMobileNavigation() {
            this.$showMobileMenu('user-navigation')
            this.$store.commit('DISABLE_MULTISELECT_MODE')
        },
    },
}
</script>
