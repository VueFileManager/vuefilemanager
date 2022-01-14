<template>
	<div v-if="user.data.relationships.failedPayments && user.data.relationships.failedPayments.data.length > 0" class="card shadow-card">
		<FormLabel icon="frown">
			{{ $t('Failed Payments') }}
		</FormLabel>

		<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
			-{{ user.data.meta.totalDebt.formatted }}
		</b>

		<b class="mb-3 block text-sm text-gray-400 mb-5">
			{{ $t("We are unable to charge your usage. Please register new credit card or fund your account with sufficient amount and we'll give it another try!") }}
		</b>

		<!--Failed Payments-->
		<div
			v-for="payment in user.data.relationships.failedPayments.data"
			:key="payment.data.id"
			class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed"
		>
			<div class="w-2/4 leading-none">
				<b class="text-sm font-bold leading-none">
					{{ payment.data.attributes.note }}
				</b>
			</div>
			<div class="text-left w-1/4">
				<span class="text-sm font-bold text-gray-560 capitalize">
					{{ $t(payment.data.attributes.source) }}
				</span>
			</div>
			<div class="text-right w-1/4">
				<span class="text-sm font-bold">
					{{ payment.data.attributes.created_at }}
				</span>
			</div>
			<div class="text-right w-1/4">
				<span class="text-sm font-bold text-red">
					{{ payment.data.attributes.amount }}
				</span>
			</div>
		</div>
	</div>
</template>

<script>
import FormLabel from "../Others/Forms/FormLabel"
import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
import {mapGetters} from "vuex";

export default {
	name: 'UserFailedPayments',
	components: {
		FormLabel,
		InfoBox,
	},
	computed: {
		...mapGetters([
			'user',
		])
	},
}
</script>