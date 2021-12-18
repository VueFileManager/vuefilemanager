<template>
	<div>
		<!--Balance-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
				{{ $t('Balance') }}
			</FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.relationships.balance.data.attributes.formatted }}
			</b>

			<ValidationObserver ref="creditUserBalance" @submit.prevent="increaseBalance" v-slot="{ invalid }" tag="form" class="mt-6">
				<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Balance Amount" rules="required">
					<AppInputText :description="$t('User balance will be increased for the amount above.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="balanceAmount"
								   :placeholder="$t('Increase user balance for...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase type="submit" button-style="theme" class="submit-button"
								:loading="isUpdatingBalanceAmount"
								:disabled="isUpdatingBalanceAmount"
							>
								{{ $t('Increase Balance') }}
							</ButtonBase>
						</div>
					</AppInputText>
				</ValidationProvider>
			</ValidationObserver>
		</div>

		<!--Usage Estimates-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
				{{ $t('Usage Estimates') }}
			</FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.meta.usages.costEstimate }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ user.data.relationships.subscription.data.attributes.updated_at }} {{ $t('till now') }}
			</b>

			<div>
				<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in user.data.meta.usages.featureEstimates" :key="i">
					<div class="w-2/4 leading-none">
						<b class="text-sm font-bold leading-none">
							{{ $t(usage.feature) }}
						</b>
						<small class="text-xs text-gray-500 pt-2 leading-none block">
							{{ $t(`feature_usage_desc_${usage.feature}`) }}
						</small>
					</div>
					<div class="text-left w-1/4">
						<span class="text-sm font-bold text-gray-560">
							{{ usage.usage }}
						</span>
					</div>
					<div class="text-right w-1/4">
						<span class="text-sm font-bold text-theme">
							{{ usage.cost }}
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import AppInputText from "../../../../components/Admin/AppInputText"
	import FormLabel from "../../../../components/Others/Forms/FormLabel"
	import ButtonBase from "../../../../components/FilesView/ButtonBase"
	import ColorLabel from "../../../../components/Others/ColorLabel"
	import {mapGetters} from "vuex";
	import axios from "axios";
	import {events} from "../../../../bus";

	export default {
		name: 'UserMeteredSubscription',
		props: [
			'subscription',
			'user',
		],
		components: {
			ValidationProvider,
			ValidationObserver,
			AppInputText,
			ButtonBase,
			ColorLabel,
			FormLabel,
		},
		computed: {
			...mapGetters([

			]),
		},
		data() {
			return {
				balanceAmount: undefined,
				isUpdatingBalanceAmount: false,
			}
		},
		methods: {
			async increaseBalance() {
				// Validate fields
				const isValid = await this.$refs.creditUserBalance.validate();

				if (!isValid) return;

				this.isUpdatingBalanceAmount = true

				axios
					.post(`/api/subscriptions/admin/users/${this.user.data.id}/credit`, {
						amount: this.balanceAmount
					})
					.then(() => {
						events.$emit('reload:user')

						this.balanceAmount = undefined

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('User balance was successfully increased'),
						})
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'success',
							message: this.$t('popup_error.title'),
						})
					})
					.finally(() => {
						this.isUpdatingBalanceAmount = false
					})
			}
		},
		created() {

		}

	}
</script>