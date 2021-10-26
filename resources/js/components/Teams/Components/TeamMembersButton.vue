<template>
	<div class="w-28">
		<div v-if="! teamFolder" class="text-center">
			<span class="dark:text-gray-500 text-gray-600 mr-0.5 text-tiny">
				{{ $t('Not selected') }}
			</span>
		</div>
		<TeamMembersPreview v-else :folder="teamFolder" :limit="true" :avatar-size="32" class="justify-center" />
	</div>
</template>

<script>
	import TeamMembersPreview from "./TeamMembersPreview"
	import {mapGetters} from "vuex"

	export default {
		name: "TeamMembersButton",
		components: {
			TeamMembersPreview,
		},
		computed: {
			...mapGetters([
				'currentTeamFolder',
				'clipboard',
			]),
			teamFolder() {
				return this.currentTeamFolder
					? this.currentTeamFolder
					: this.clipboard[0]
			}
		},
	}
</script>