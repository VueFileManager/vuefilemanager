<template>
	<div class="team-folder">
		<span v-if="count" class="member-count">
			{{ count }}
		</span>
		<div class="members">
			<img v-for="member in members" :src="member" class="member">
		</div>
	</div>
</template>

<script>
export default {
	name: "TeamMembersPreview",
	props: [
		'members',
		'count',
	]
}
</script>

<style lang="scss" scoped>
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.team-folder {
		display: flex;
		align-items: center;

		.member-count {
			@include font-size(12);
			color: $text-muted;
			margin-right: 3px;
			opacity: 0.7;
		}

		.members {

			.member {
				width: 32px;
				height: 32px;
				object-fit: cover;
				border-radius: 10px;
				margin-left: -10px;
				border: 2px solid white;
				vertical-align: middle;

				&:first-child {
					margin-left: 0;
				}
			}
		}
	}
</style>