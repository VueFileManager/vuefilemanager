<template>
    <div class="w-28">
        <div v-if="!teamFolder" class="text-right md:text-center">
            <span class="mr-3 align-middle text-tiny text-gray-600 dark:text-gray-500 md:mr-0.5">
                {{ $t('not_selected') }}
            </span>
        </div>
        <TeamMembersPreview
            v-else
            :folder="teamFolder"
            :limit="true"
            :avatar-size="size"
            class="justify-end md:justify-center"
        />
    </div>
</template>

<script>
import TeamMembersPreview from './TeamMembersPreview'
import { mapGetters } from 'vuex'

export default {
    name: 'TeamMembersButton',
    components: {
        TeamMembersPreview,
    },
    props: ['size'],
    computed: {
        ...mapGetters(['currentTeamFolder', 'clipboard']),
        teamFolder() {
            return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
        },
    },
}
</script>
