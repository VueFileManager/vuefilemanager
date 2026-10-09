<template>
    <MenuMobile name="team-menu">
        <TeamFolderPreview />

        <MenuMobileGroup v-if="$slots.default">
            <slot />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '../Mobile/MenuMobileGroup.vue'
import TeamFolderPreview from '../Teams/Components/TeamFolderPreview.vue'
import MenuMobile from '../Mobile/MenuMobile.vue'

export default {
    name: 'MobileTeamContextMenu',
    components: {
        TeamFolderPreview,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
