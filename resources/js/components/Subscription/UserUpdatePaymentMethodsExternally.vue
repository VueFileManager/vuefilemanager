<template>
	<div v-if="['paystack', 'paypal'].includes(subscription.attributes.driver)" class="card shadow-card">
		<FormLabel>
			{{ $t('Update Payments') }}
		</FormLabel>

		<AppInputSwitch :title="$t('Update your Payment Method')" :description="$t('You will be redirected to your payment provider to edit your payment method.')" :is-last="true">
			<ButtonBase @click.native="updatePaymentMethod" :loading="isGeneratedUpdateLink" class="sm:w-auto w-full" button-style="theme">
				{{ $t('Update Payments') }}
			</ButtonBase>
		</AppInputSwitch>
	</div>
</template>

<script>
	import AppInputSwitch from "../Admin/AppInputSwitch"
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import FormLabel from "../Others/Forms/FormLabel"
	import axios from "axios";
	import {events} from "../../bus";

	export default {
		name: 'UserUpdatePaymentMethodsExternally',
		components: {
			AppInputSwitch,
			ButtonBase,
			FormLabel
		},
		computed: {
			subscription() {
				return this.$store.getters.user.data.relationships.subscription.data
			}
		},
		data() {
			return {
				isGeneratedUpdateLink: false,
			}
		},
		methods: {
			updatePaymentMethod() {

				this.isGeneratedUpdateLink = true

				axios.post(`/api/subscriptions/edit/${this.subscription.id}`)
					.then(response => {
						window.location = response.data.url
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})

						this.isGeneratedUpdateLink = false
					})
			},
		}
	}
</script>