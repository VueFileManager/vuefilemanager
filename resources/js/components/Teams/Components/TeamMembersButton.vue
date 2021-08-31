<template>
	<div class="team-folder-wrapper">
		<div v-if="! teamFolder" class="empty-state">
			<span>Not selected</span>
		</div>
		<TeamMembersPreview v-else :folder="teamFolder" :limit="true" :avatar-size="32" class="widget" />
	</div>
</template>

<script>
	import {mapGetters} from "vuex";
	import TeamMembersPreview from "./TeamMembersPreview";

	export default {
		name: "TeamMembersButton",
		components: {
			TeamMembersPreview
		},
		computed: {
			...mapGetters([
				'currentTeamFolder',
				'clipboard',
			]),
			teamFolder() {
				return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
			}
		},
	}
</script>

<style lang="scss" scoped>
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.team-folder-wrapper {
		width: 107px;

		.widget {
			justify-content: center;
		}
	}

	.empty-state {
		text-align: center;

		span {
			@include font-size(12);
			color: $text-muted;
			opacity: 0.7;
			margin-right: 7px;
		}
	}
</style>