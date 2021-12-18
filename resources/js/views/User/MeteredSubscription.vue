<template>
    <PageTab :is-loading="isLoading">

		<!--Balance-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Balance') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.relationships.balance.data.attributes.formatted }}
			</b>

			<ValidationObserver ref="changeStorageCapacity" @submit.prevent="makePayment" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
					<AppInputText :description="$t('The amount will be increased as soon as we register your charge from payment gateway.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="paymentAmount"
								   :placeholder="$t('Fund Your Account Balance...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase type="submit" button-style="theme" class="submit-button">
								{{ $t('Make a Payment') }}
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

		<!--Billing Alert-->
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
						<div class="flex space-x-4">
							<input v-model="billingAlertAmount"
								   :placeholder="$t('Alert Amount...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="submit-button">
								{{ $t('Update Alert') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>

			<ValidationObserver v-if="! user.data.relationships.alert" ref="billingAlertForm" @submit.prevent="setBillingAlert" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
					<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="billingAlertAmount"
								   :placeholder="$t('Alert Amount...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="submit-button">
								{{ $t('Set Alert') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
		</div>

		<!--Transactions-->
		<div class="card shadow-card">
			<FormLabel icon="file-text">
                {{ $t('Transactions') }}
            </FormLabel>

			<DatatableWrapper
				@init="isLoading = false"
				api="/api/subscriptions/transactions"
				:paginator="true"
				:columns="columns"
			>
                <template slot-scope="{ row }">
                    <tr class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td class="py-4">
                            <span class="text-sm font-bold">
								{{ row.data.attributes.note }}
							</span>
                        </td>
                        <td>
							<ColorLabel :color="$getTransactionStatusColor(row.data.attributes.status)">
                                {{ row.data.attributes.status }}
							</ColorLabel>
                        </td>
                        <td>
                            <span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
                                {{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="text-right">
                            <img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
                        </td>
                    </tr>
                </template>

				<!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox>
                        <p>{{ $t('admin_page_user.invoices.empty') }}</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
		</div>
    </PageTab>
</template>

<script>
    import {
		XIcon,
		Trash2Icon,
		Edit2Icon,
	} from "vue-feather-icons";
	import ColorLabel from "../../components/Others/ColorLabel";
	import DatatableWrapper from "../../components/Others/Tables/DatatableWrapper";
	import AppInputText from "../../components/Admin/AppInputText";
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import AppInputSwitch from "../../components/Admin/AppInputSwitch"
	import FormLabel from "../../components/Others/Forms/FormLabel"
	import ProgressLine from "../../components/Admin/ProgressLine"
	import {mapGetters} from 'vuex'
	import axios from 'axios'
	import {events} from "../../bus";

	export default {
		name: 'MeteredSubscription',
		components: {
			DatatableWrapper,
			ValidationProvider,
			ValidationObserver,
			AppInputSwitch,
			AppInputText,
			ProgressLine,
			ButtonBase,
			ColorLabel,
			Trash2Icon,
			Edit2Icon,
			XIcon,
			FormLabel,
			InfoBox,
			PageTab,
		},
		computed: {
			...mapGetters([
				'user',
			]),
		},
		data() {
			return {
				isLoading: false,

				isSendingBillingAlert: false,
				billingAlertAmount: undefined,
				showUpdateBillingAlertForm: false,

				paymentAmount: undefined,
				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('Status'),
						field: 'status',
						sortable: true
					},
					{
						label: this.$t('admin_page_invoices.table.total'),
						field: 'amount',
						sortable: true
					},
					{
						label: this.$t('Payed At'),
						field: 'created_at',
						sortable: true
					},
					{
						label: this.$t('Service'),
						field: 'driver',
						sortable: true
					},
				],
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
							type: 'success',
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
							type: 'success',
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
			makePayment() {
				// TODO: make a payment
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
