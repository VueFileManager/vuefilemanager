<template>
	<div class="team-folder-preview">
		<div class="info">
			<b class="title">The Team Project</b>
			<span class="subtitle">Last update a week ago</span>
			<TeamMembersPreview :members="fullMembers" class="members" />
		</div>
	</div>
</template>

<script>
	import TeamMembersPreview from "./TeamMembersPreview";

	export default {
		name: "TeamFolderPreview",
		components: {
			TeamMembersPreview,
		},
		data() {
			return {
				fullMembers: [
					'/temp/avatar-01.png',
					'/temp/avatar-02.png',
					'/temp/avatar-03.png',
					'/temp/avatar-01.png',
					'/temp/avatar-02.png',
					'/temp/avatar-03.png',
				],
			}
		},
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