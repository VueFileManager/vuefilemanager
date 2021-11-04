<template>
    <MenuMobile name="team-menu">
		<TeamFolderPreview />

		<MenuMobileGroup v-if="$slots.default">
			<slot></slot>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '/resources/js/components/Mobile/MenuMobileGroup'
import TeamFolderPreview from '../Teams/Components/TeamFolderPreview'
import MenuMobile from '/resources/js/components/Mobile/MenuMobile'

export default {
    name: 'MobileTeamContextMenu',
    components: {
		TeamFolderPreview,
        MenuMobileGroup,
		MenuMobile,
    },
}
</script>
