<template>
	<div class="w-28">
		<div v-if="! teamFolder" class="md:text-center text-right">
			<span class="dark:text-gray-500 text-gray-600 md:mr-0.5 mr-3 text-tiny align-middle">
				{{ $t('Not selected') }}
			</span>
		</div>
		<TeamMembersPreview v-else :folder="teamFolder" :limit="true" :avatar-size="size" class="md:justify-center justify-end" />
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
		props: [
			'size'
		],
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