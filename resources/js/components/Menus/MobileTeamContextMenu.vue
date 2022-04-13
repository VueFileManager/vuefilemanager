<template>
    <MenuMobile name="team-menu">
        <TeamFolderPreview />

        <MenuMobileGroup v-if="$slots.default">
            <slot />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import TeamFolderPreview from '../Teams/Components/TeamFolderPreview'
import MenuMobile from '../Mobile/MenuMobile'

export default {
    name: 'MobileTeamContextMenu',
    components: {
        TeamFolderPreview,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
