<template>
	<div class="team-folder-preview text-left py-3 px-5">
		<div class="info">
			<b class="title text-sm">
				{{ teamFolder.data.attributes.name }}
			</b>
			<span class="subtitle text-tiny block mb-2 dark:text-gray-500 text-gray-600">
				Created at {{ teamFolder.data.attributes.created_at }}
			</span>
			<TeamMembersPreview :folder="teamFolder" :avatar-size="32" class="members" />
		</div>
	</div>
</template>

<script>
	import TeamMembersPreview from "./TeamMembersPreview";
	import {mapGetters} from "vuex";

	export default {
		name: "TeamFolderPreview",
		components: {
			TeamMembersPreview,
		},
		computed: {
			...mapGetters([
				'currentTeamFolder',
				'clipboard',
			]),
			teamFolder() {
				return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
			}
		}
	}
</script>

<style lang="scss" scoped>
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.team-folder-preview {
		border-bottom: 1px solid $light_mode_border;
	}

	.dark {
		.team-folder-preview {
			border-color: $dark_mode_border_color;
		}
	}
</style>