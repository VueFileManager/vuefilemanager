<template>
	<div class="team-folder-preview">
		<div class="info">
			<b class="title">{{ teamFolder.data.attributes.name }}</b>
			<span class="subtitle">Created at {{ teamFolder.data.attributes.created_at }}</span>
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
		text-align: left;
		padding: 15px 20px;
		border-bottom: 1px solid $light_mode_border;

		.info {

			.title {
				@include font-size(14);
			}

			.subtitle {
				@include font-size(11);
				color: $text-muted;
				display: block;
				margin-bottom: 10px;
				line-height: 1;
			}
		}
	}

	.dark-mode {
		.team-folder-preview {
			border-color: $dark_mode_border_color;
		}
	}
</style>