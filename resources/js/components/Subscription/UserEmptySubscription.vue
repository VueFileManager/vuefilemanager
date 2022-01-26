<template>
	<div v-if="! hasSubscription" class="card shadow-card">
		<FormLabel>
			{{ $t('Subscription') }}
		</FormLabel>

		<b class="sm:text-3xl text-2xl font-extrabold -mt-3 block mb-0.5">
			{{ $t('Free Plan') }}
		</b>

		<b class="mb-3 block text-sm text-gray-400 mb-8">
			{{ $t('1GB Free storage space with 5 Team members') }}
		</b>

		<ButtonBase @click.native="$openUpgradeOptions" type="submit" button-style="theme" class="w-full mt-4">
			{{ $t('Upgrade Your Account') }}
		</ButtonBase>
	</div>
</template>

<script>
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import FormLabel from "../Others/Forms/FormLabel"
	import ButtonBase from "../FilesView/ButtonBase"

	export default {
		name: 'UserEmptySubscription',
		components: {
			ButtonBase,
			FormLabel,
			InfoBox,
		},
		computed: {
			hasSubscription() {
				return this.$store.getters.user.data.relationships.subscription
			},
		},
	}
</script>