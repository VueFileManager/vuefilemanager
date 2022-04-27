<template>
    <div
        class="sticky top-0 z-[19] block flex w-full items-center justify-between bg-white py-5 px-4 text-center dark:bg-dark-background lg:hidden"
    >
        <NavigationBar />

        <div class="relative flex items-center">
            <TeamMembersButton
                v-if="$isThisRoute($route, ['TeamFolders', 'SharedWithMe'])"
                size="28"
                @click.stop.native="$showMobileMenu('team-menu')"
                class="absolute right-10"
            />

            <!--More Actions-->
            <div class="flex items-center relative mr-[4px]">
				<div v-if="! $isThisRoute($route, ['Public'])" @click="showMobileNavigation" class="cursor-pointer p-1.5 -m-1.5">
					<menu-icon size="17" class="vue-feather dark:text-gray-100" />
				</div>
            </div>
        </div>
    </div>
</template>

<script>
import TeamMembersPreview from '../../Teams/Components/TeamMembersPreview'
import TeamMembersButton from '../../Teams/Components/TeamMembersButton'
import { MenuIcon } from 'vue-feather-icons'
import NavigationBar from './NavigationBar'

export default {
    name: 'MobileToolBar',
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
