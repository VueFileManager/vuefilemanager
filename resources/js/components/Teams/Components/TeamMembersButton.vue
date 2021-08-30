<template>
	<div class="team-folder-wrapper">
		<div v-if="! clipboard[0]" class="empty-state">
			<span>Not selected</span>
		</div>
		<TeamMembersPreview v-else :folder="clipboard[0]" :limit="true" class="widget" />
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
				'clipboard'
			])
		},
	}
</script>

<style lang="scss" scoped>
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.team-folder-wrapper {
		width: 107px;

		.widget {
			justify-content: flex-end;
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