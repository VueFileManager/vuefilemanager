<template>
	<div class="empty-message">
		<div class="message">
			<FontAwesomeIcon class="icon" :icon="icon" />
			<p>{{ message }}</p>
		</div>
	</div>
</template>

<script>
export default {
	name: 'EmptyMessage',
	props: ['icon', 'message']
}
</script>

<style scoped lang="scss">
	@import "@assets/app.scss";

	.empty-message {
	text-align: center;
	display: flex;
	align-items: center;
	height: 100%;

	.message {
		margin: 0 auto;

		p {
			margin-top: 10px;
			max-width: 130px;
			@include font-size(14);
			font-weight: 500;
			color: $text-muted;
		}

		.icon {
			@include font-size(36);
			color: $text;

			path {
				color: $text;
			}
		}
	}
}
</style>
