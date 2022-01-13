<template>
	<div class="flex items-center justify-between py-3 px-4 input-dark">
		<div class="flex items-center">
			<img :src="`/assets/gateways/${card.data.attributes.brand}.svg`" alt="" class="h-5 mr-3 rounded">
			<b class="text-sm font-bold leading-none capitalize">
				{{ card.data.attributes.brand }} •••• {{ card.data.attributes.last4 }}
			</b>
		</div>
		<b class="text-sm font-bold leading-none">
			{{ $t('Expires') }} {{ card.data.attributes.expiration }}
		</b>
		<Trash2Icon @click="deleteCreditCard(card.data.id)" size="15" class="cursor-pointer" />
	</div>
</template>
<script>
    import {Trash2Icon,} from "vue-feather-icons";
	import {events} from "../../bus";
	import axios from "axios";

	export default {
		name: 'PaymentCard',
		components: {
			Trash2Icon,
		},
		props: [
			'card',
		],
		methods: {
			deleteCreditCard(id) {
				events.$emit('confirm:open', {
					title: this.$t('Are you sure you want to delete your credit card?'),
					message: this.$t('We will no longer settle your payments automatically and you will have to fund your account for the next payments.'),
					action: {
						id: id,
						operation: 'delete-credit-card',
					}
				})
			},
		},
		created() {
			events.$on('action:confirmed', data => {
				if (data.operation === 'delete-credit-card')
					axios.delete(`/api/stripe/credit-cards/${data.id}`)
						.then(() => {
							this.$store.dispatch('getAppData')

							events.$emit('toaster', {
								type: 'success',
								message: this.$t('Your credit card was deleted.'),
							})
						})
						.catch(() => this.$isSomethingWrong())
			})
		},
		destroyed() {
			events.$off('action:confirmed')
		},
	}
</script>