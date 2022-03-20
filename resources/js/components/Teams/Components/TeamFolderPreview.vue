<template>
    <div class="py-3 px-5 text-left">
        <div class="info">
            <b class="title text-sm">
                {{ teamFolder.data.attributes.name }}
            </b>
            <span class="subtitle mb-2 block text-tiny text-gray-600 dark:text-gray-500">
                {{ $t('created_at') }} {{ teamFolder.data.attributes.created_at }}
            </span>
            <TeamMembersPreview :folder="teamFolder" :avatar-size="32" class="members" />
        </div>
    </div>
</template>

<script>
import TeamMembersPreview from './TeamMembersPreview'
import { mapGetters } from 'vuex'

export default {
    name: 'TeamFolderPreview',
    components: {
        TeamMembersPreview,
    },
    computed: {
        ...mapGetters(['currentTeamFolder', 'clipboard']),
        teamFolder() {
            return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
        },
    },
}
</script>
