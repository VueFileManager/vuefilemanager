<template>
	<div v-if="! hasSubscription" class="card shadow-card">
		<InfoBox style="margin-bottom: 0">
			<p>{{ $t("You don't have any subscription") }}</p>
		</InfoBox>
	</div>
</template>

<script>
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'

	export default {
		name: 'UserEmptySubscription',
		components: {
			InfoBox
		},
		computed: {
			hasSubscription() {
				return this.$store.getters.user.data.relationships.subscription
			},
		},
	}
</script>