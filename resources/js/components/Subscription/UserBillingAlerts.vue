<template>
	<div class="card shadow-card">
		<FormLabel icon="bell">
			{{ $t('Billing Alert') }}
		</FormLabel>

		<div v-if="user.data.relationships.alert">
			<b class="text-3xl font-extrabold -mt-3 block mb-0.5 flex items-center">
				{{ user.data.relationships.alert.data.attributes.formatted }}
				<edit2-icon v-if="! showUpdateBillingAlertForm" @click="showUpdateBillingAlertForm = ! showUpdateBillingAlertForm" size="12" class="vue-feather cursor-pointer ml-2 transform -translate-y-0.5" />
				<trash2-icon v-if="showUpdateBillingAlertForm" @click="deleteBillingAlert" size="12" class="vue-feather cursor-pointer ml-2 transform -translate-y-0.5" />
			</b>

			<b class="block text-sm text-gray-400">
				{{ $t('Alert will be triggered after you reach the value above.') }}
			</b>
		</div>

		<ValidationObserver v-if="showUpdateBillingAlertForm" ref="updatebillingAlertForm" @submit.prevent="updateBillingAlert" v-slot="{ invalid }" tag="form" class="mt-6">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
				<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
					<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
						<input v-model="billingAlertAmount"
							   :placeholder="$t('Alert Amount...')"
							   type="number"
							   min="1"
							   max="999999999"
							   class="focus-border-theme input-dark"
							   :class="{'border-red': errors[0]}"
						/>
						<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="sm:w-auto w-full">
							{{ $t('Update Alert') }}
						</ButtonBase>
					</div>
				</AppInputText>
			</ValidationProvider>
		</ValidationObserver>

		<ValidationObserver v-if="! user.data.relationships.alert" ref="billingAlertForm" @submit.prevent="setBillingAlert" v-slot="{ invalid }" tag="form" class="mt-6">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
				<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
					<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
						<input v-model="billingAlertAmount"
							   :placeholder="$t('Alert Amount...')"
							   type="number"
							   min="1"
							   max="999999999"
							   class="focus-border-theme input-dark"
							   :class="{'border-red': errors[0]}"
						/>
						<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="sm:w-auto w-full">
							{{ $t('Set Alert') }}
						</ButtonBase>
					</div>
				</AppInputText>
			</ValidationProvider>
		</ValidationObserver>
	</div>
</template>
<script>
import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
import AppInputText from "../Admin/AppInputText"
import FormLabel from "../Others/Forms/FormLabel"
import { Edit2Icon, Trash2Icon } from 'vue-feather-icons'
import {events} from "../../bus";
import {mapGetters} from "vuex";
import axios from "axios";

export default {
	name: 'UserBillingAlerts',
	components: {
		ValidationObserver,
		ValidationProvider,
		AppInputText,
		ButtonBase,
		Trash2Icon,
		Edit2Icon,
		FormLabel,
	},
	computed: {
		...mapGetters([
			'user',
		]),
	},
	data() {
		return {
			billingAlertAmount: undefined,
			isSendingBillingAlert: false,
			showUpdateBillingAlertForm: false,
		}
	},
	methods: {
		async updateBillingAlert() {

			// Validate fields
			const isValid = await this.$refs.updatebillingAlertForm.validate();

			if (!isValid) return;

			this.isSendingBillingAlert = true

			axios
				.patch(`/api/subscriptions/billing-alerts/${this.user.data.relationships.alert.data.id}`, {
					amount: this.billingAlertAmount
				})
				.then(() => {
					this.$store.dispatch('getAppData')

					this.showUpdateBillingAlertForm = false

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('Your billing alert was updated successfully'),
					})
				})
				.catch(() => {
					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('popup_error.title'),
					})
				})
				.finally(() => {
					this.isSendingBillingAlert = false
				})
		},
		async setBillingAlert() {

			// Validate fields
			const isValid = await this.$refs.billingAlertForm.validate();

			if (!isValid) return;

			this.isSendingBillingAlert = true

			axios
				.post('/api/subscriptions/billing-alerts', {
					amount: this.billingAlertAmount
				})
				.then(() => {
					this.$store.dispatch('getAppData')

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('Your billing alert was set successfully'),
					})
				})
				.catch(() => {
					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('popup_error.title'),
					})
				})
				.finally(() => {
					this.isSendingBillingAlert = false
				})
		},
		deleteBillingAlert() {
			events.$emit('confirm:open', {
				title: this.$t('Are you sure you want to delete your alert?'),
				message: this.$t('You will no longer receive any notifications that your billing limit has been exceeded.'),
				action: {
					id: this.user.data.relationships.alert.data.id,
					operation: 'delete-billing-alert',
				}
			})
		},
	},
	created() {
		events.$on('action:confirmed', data => {

			if (data.operation === 'delete-billing-alert')
				axios.delete(`/api/subscriptions/billing-alerts/${this.user.data.relationships.alert.data.id}`)
					.then(() => {
						this.$store.dispatch('getAppData')

						this.showUpdateBillingAlertForm = false
						this.billingAlertAmount = undefined

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('Your billing alert was deleted.'),
						})
					})
					.catch(() => this.$isSomethingWrong())
		})
	}
}
</script>