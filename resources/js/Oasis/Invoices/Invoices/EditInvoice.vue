<template>
	<div id="single-page">
		<MobileHeader :title="pageTitle" />
		<PageHeader :can-back="true" :title="pageTitle" />

		<div id="page-content">
			<div class="content-page" v-if="! isLoadingPage">
				<ValidationObserver @submit.prevent="updateInvoice" ref="updateInvoice" v-slot="{ invalid }" tag="form" class="form block-form">
					<PageTab>

						<!--Properties-->
						<PageTabGroup>
							<FormLabel icon="tool">{{ $t('in_editor.properties') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_number') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="invoice_number" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.invoice_number" :placeholder="$t('in_editor.plac.invoice_number')" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<small v-if="latestInvoiceNumber" class="input-help">
										{{ $t('in_number_desc', {number: latestInvoiceNumber}) }}
									</small>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_variable') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="variable_number" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.variable_number" :placeholder="$t('in_editor.plac.variable_number')" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<small v-if="latestInvoiceNumber" class="input-help">
										{{ $t('in_variable_desc') }}
									</small>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_delivery_at') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="delivery_at" rules="required" v-slot="{ errors }">
									<input v-model="invoice.delivery_at" type="date" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>

						<!--Client-->
						<PageTabGroup>
							<FormLabel icon="user">{{ $t('in_editor.client') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client') }}:</label>
								<div class="input-wrapper">
									<input v-model.number="client['name']" type="text" disabled />
								</div>
							</div>
						</PageTabGroup>

						<!--Items-->
						<PageTabGroup>
							<FormLabel icon="edit">{{ $t('in_editor.items') }}</FormLabel>

							<div class="duplicator">
								<div class="plan-item duplicator-item" v-for="(item, index) in invoice.items" :key="index++">
									<x-icon @click="removeRow(item)" v-if="index !== 1" size="22" class="delete-item hover-text-theme" />

									<div class="block-wrapper">
										<label>{{ $t('in_editor.description') }}:</label>
										<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="description" rules="required" v-slot="{ errors }">
											<input v-model="item.description" ref="duplicatorItemTitle" :placeholder="$t('in_editor.plac.item_desc')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
											<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
										</ValidationProvider>
									</div>

									<div class="wrapper-inline">
										<div class="block-wrapper">
											<label>{{ $t('in_editor.amount') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="amount" rules="required" v-slot="{ errors }">
												<input v-model="item.amount" :placeholder="$t('in_editor.plac.item_amount')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div class="block-wrapper">
											<label>{{ $t('in_editor.unit') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="unit" rules="required" v-slot="{ errors }">
												<input v-model="item.unit" :placeholder="$t('in_editor.plac.item_unit')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div v-if="isVatPayer" class="block-wrapper">
											<label>{{ $t('in_editor.tax_rate') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="tax_rate" rules="required" v-slot="{ errors }">
												<input v-model="item.tax_rate" :placeholder="$t('in_editor.plac.item_tax_rate')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div class="block-wrapper">
											<label>{{ $t('in_editor.price') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="price" rules="required" v-slot="{ errors }">
												<input v-model="item.price" :placeholder="$t('in_editor.plac.item_price')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>
									</div>
								</div>

								<ButtonBase @click.native="addRow" class="duplicator-add-button" button-style="theme">
									{{ $t('in_editor.add_item') }}
								</ButtonBase>
							</div>
						</PageTabGroup>

						<!--Discount-->
						<PageTabGroup>
							<FormLabel icon="credit-card">
								{{ $t('in_editor.discount') }}
							</FormLabel>

							<div class="block-wrapper">
								<div class="input-wrapper">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">{{ $t('in_editor.apply_discount') }}:</label>
											<small class="input-help">{{ $t('in_editor.discount_help') }}</small>
										</div>
										<SwitchInput v-model="isDiscount" class="switch" :state="isDiscount" />
									</div>
								</div>
							</div>

							<div v-if="isDiscount" class="block-wrapper">
								<label>{{ $t('in_editor.discount_type') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="discount_type" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.discount_type" :default="invoice.discount_type" :options="discountTypeList" :placeholder="$t('in_editor.plac.discount_type')" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isDiscount" class="block-wrapper">
								<label>{{ $t('in_editor.discount_rate') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="discount_rate" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.discount_rate" :placeholder="$t('in_editor.plac.discount_rate')" max="100" min="0" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>

						<!--Others-->
						<PageTabGroup>
							<FormLabel icon="settings">{{ $t('in_editor.others') }}</FormLabel>

							<div v-if="invoice.client_email" class="block-wrapper">
								<div class="input-wrapper">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">{{ $t('in_editor.store_client') }}:</label>
											<small class="input-help">{{ $t('in_editor.store_client_notes') }}</small>
										</div>
										<SwitchInput v-model="invoice.send_invoice" class="switch" :state="invoice.send_invoice" />
									</div>
								</div>
							</div>

							<div class="block-wrapper">
								<div class="input-wrapper">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">{{ $t('in_editor.send') }}:</label>
											<small class="input-help">{{ $t('in_editor.send_notes') }}</small>
										</div>
										<SwitchInput v-model="invoice.send_invoice" class="switch" :state="invoice.send_invoice" />
									</div>
								</div>
							</div>
						</PageTabGroup>
					</PageTab>
				</ValidationObserver>
				<div class="summary">
					<FormLabel icon="credit-card">
						{{ $t('in_editor.summary') }}
					</FormLabel>
					<div class="summary-list" :class="{'is-error': isError}">

						<div v-if="isDiscount && invoice.discount_rate > 0" class="row small">
							<div class="cell">
								<span>{{ $t('in_editor.discount') }}</span>
							</div>
							<div class="cell">
								<span>-{{ invoice.discount_type === 'percent' ? formatNumber(invoice.discount_rate) + '%' : formatCurrency(invoice.discount_rate) }}</span>
							</div>
						</div>

						<div v-if="isVatPayer" :class="{'is-offset': isDiscount && invoice.discount_rate > 0}">
							<div v-for="(tax, i) in taxBased" :key="i" class="row small">
								<div class="cell">
									<span>{{ $t('in_editor.summary.vat_base') }} {{ tax.rate }}%</span>
								</div>
								<div class="cell">
									<span>{{ formatCurrency(tax.total) }}</span>
								</div>
							</div>
						</div>

						<div v-if="isVatPayer" :class="{'is-offset': taxSummary.length > 1}">
							<div v-for="(tax, i) in taxSummary" :key="i" class="row small">
								<div class="cell">
									<span>{{ $t('in_editor.summary.vat') }} {{ tax.rate }}%</span>
								</div>
								<div class="cell">
									<span>{{ formatCurrency(tax.total) }}</span>
								</div>
							</div>
						</div>

						<div class="row" :class="{'row-summary': total > 0}">
							<div class="cell">
								<b>{{ $t('in_editor.summary.total') }}</b>
							</div>
							<div class="cell">
								<b>{{ formatCurrency(total) }}</b>
							</div>
						</div>

						<ButtonBase :disabled="isLoading" @click.native="deleteInvoice" button-style="secondary" class="next-submit delete-dark">
							{{ $t('in.form.delete_invoice') }}
						</ButtonBase>

						<ButtonBase :disabled="isLoading" :loading="isLoading" @click.native="updateInvoice" button-style="theme-solid" class="next-submit" style="margin-top: 15px">
							{{ $t('popup_share_edit.save') }}
						</ButtonBase>
						<p class="error-message" v-if="isError">
							{{ errorMessage }}
						</p>
					</div>
				</div>
			</div>
			<div id="loader" v-if="isLoadingPage">
				<Spinner />
			</div>
			<div v-if="!isLoadingPage && total > 0" class="total-wrapper bg-theme-100">
				<b>{{ $t('in.doc.sum_to_pay') }}:</b>
				<b>{{ formatCurrency(total) }}</b>
			</div>
		</div>
	</div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
	import SwitchInput from '@/components/Others/Forms/SwitchInput'
	import SelectInput from '@/components/Others/Forms/SelectInput'
	import ImageInput from '@/components/Others/Forms/ImageInput'
	import FormLabel from '@/components/Others/Forms/FormLabel'
	import MobileHeader from '@/components/Mobile/MobileHeader'
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import PageTab from '@/components/Others/Layout/PageTab'
	import PageHeader from '@/components/Others/PageHeader'
	import InfoBox from '@/components/Others/Forms/InfoBox'
	import Spinner from '@/components/FilesView/Spinner'
	import {required} from 'vee-validate/dist/rules'
	import {XIcon} from 'vue-feather-icons'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"
	import axios from "axios"
	import {debounce} from "lodash"

	export default {
		name: 'CreateInvoice',
		components: {
			ValidationProvider,
			ValidationObserver,
			PageTabGroup,
			MobileHeader,
			SelectInput,
			SwitchInput,
			ImageInput,
			PageHeader,
			ButtonBase,
			FormLabel,
			required,
			Spinner,
			InfoBox,
			PageTab,
			XIcon,
		},
		computed: {
			...mapGetters([
				'countries',
				'config',
			]),
			pageTitle() {
				return {
					'regular-invoice': this.$t('in_editor.page.edit_regular_invoice'),
					'advance-invoice': this.$t('in_editor.page.edit_advance_invoice'),
				}[this.invoice.invoice_type]
			},
			taxBased() {
				let bag = [];

				this.invoice.items.forEach(item => {

					if (item.price && item.amount && item.tax_rate) {

						if (!bag.find(bagItem => bagItem.rate === item.tax_rate)) {
							bag.push({
								rate: item.tax_rate,
								total: (this.$parseFloat(item.price) * this.$parseFloat(item.amount)),
							})
						} else {
							bag.find(bagItem => {

								// Count total tax rate for percentage
								if (bagItem.rate === item.tax_rate) {
									bagItem.total += (this.$parseFloat(item.price) * this.$parseFloat(item.amount))
								}
							})
						}
					}
				})

				// Count discount
				if (this.isDiscount) {
					bag.forEach(bagItem => {

						if (this.invoice.discount_type === 'percent') {
							bagItem.total -= bagItem.total * (this.invoice.discount_rate / 100)
						}

						if (this.invoice.discount_type === 'value') {
							let percentage_of_discount = this.invoice.discount_rate / (this.total + this.invoice.discount_rate)

							bagItem.total -= bagItem.total * percentage_of_discount
						}
					})
				}

				return bag
			},
			taxSummary() {
				let bag = [];

				this.invoice.items.forEach(item => {

					if (item.price && item.amount && item.tax_rate) {

						if (!bag.find(bagItem => bagItem.rate === item.tax_rate)) {

							bag.push({
								rate: item.tax_rate,
								total: (this.$parseFloat(item.price) * this.$parseFloat(item.amount)) * (item.tax_rate / 100),
							})

						} else {

							bag.map(bagItem => {

								// Count total tax rate for percentage
								if (bagItem.rate === item.tax_rate) {
									bagItem.total += (this.$parseFloat(item.price) * this.$parseFloat(item.amount)) * (item.tax_rate / 100)
								}
							})
						}
					}
				})

				// Count discount
				if (this.isDiscount) {
					bag.forEach(bagItem => {

						if (this.invoice.discount_type === 'percent') {
							bagItem.total -= bagItem.total * (this.invoice.discount_rate / 100)
						}

						if (this.invoice.discount_type === 'value') {
							let percentage_of_discount = this.invoice.discount_rate / (this.total + this.invoice.discount_rate)

							bagItem.total -= bagItem.total * percentage_of_discount
						}
					})
				}

				return bag
			},
			total() {
				let total = 0;

				this.invoice.items.forEach(item => {
					if (item.price && item.amount) {

						let total_without_tax = (this.$parseFloat(item.price) * this.$parseFloat(item.amount))

						// Count tax
						if (this.isVatPayer && item.tax_rate) {
							total_without_tax += total_without_tax * (item.tax_rate / 100)
						}

						total += total_without_tax
					}
				})

				// Count discount
				if (this.isDiscount) {

					if (this.invoice.discount_type === 'percent') {
						total -= total * (this.invoice.discount_rate / 100)
					}

					if (this.invoice.discount_type === 'value') {
						total -= this.invoice.discount_rate
					}
				}

				return total
			}
		},
		data() {
			return {
				fullDetails: undefined,
				isLoadingPage: true,
				isLoading: false,
				isError: false,
				isDiscount: false,
				isVatPayer: false,
				clients: [],
				latestInvoiceNumber: undefined,
				invoice: {
					invoice_type: '',
					invoice_number: '',
					variable_number: '',
					delivery_at: '',
					items: [
						{
							id: Math.floor(Math.random() * 10000000),
							description: '',
							amount: 1,
							tax_rate: 21,
							price: undefined,
						}
					],
					discount_type: undefined,
					discount_rate: undefined,
					send_invoice: false,
				},
				client: '',
				discountTypeList: [
					{
						label: this.$t('in_editor.discount_type_percent'),
						value: 'percent',
					},
					{
						label: this.$t('in_editor.discount_type_amount'),
						value: 'value',
					},
				],
			}
		},
		methods: {
			formatCurrency(value) {
				return new Intl
					.NumberFormat('cs-CS', {
						style: 'currency',
						currency: this.config.invoicingCurrency
					})
					.format(value)
			},
			formatNumber(value) {
				return (Math.round(value * 100) / 100)
					.toFixed(2);
			},
			deleteInvoice() {
				events.$emit('confirm:open', {
					title: this.$t('in.popup.delete_single_invoice.title'),
					message: this.$t('in.popup.delete_single_invoice.message'),
					buttonColor: 'danger-solid',
					action: {
						id: this.$route.params.id,
						operation: 'delete-invoice'
					}
				})
			},
			async updateInvoice() {
				const isValid = await this.$refs.updateInvoice.validate();

				if (!isValid) {
					this.isError = true
					this.errorMessage = this.$t('in_editor.error')
					return
				}

				// Start loading
				this.isLoading = true

				// Send request to get user token
				axios
					.put(`/api/v1/invoicing/invoices/${this.$route.params.id}`, this.invoice)
					.then(() => {

						this.$store.dispatch({
							'regular-invoice': 'getRegularInvoices',
							'advance-invoice': 'getAdvanceInvoices',
						}[this.invoice.invoice_type])

						this.$router.push({name: 'InvoicesList'})

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('in_toaster.success_invoice_edition'),
						})
					})
					.catch(error => {
						this.isError = true

						if (error.response.status === 422) {

							Object.keys(error.response.data.errors).forEach(key => {

								let obj = {};
								obj[key] = error.response.data.errors[key]

								this.$refs.updateInvoice.setErrors(obj);
							})

						} else {
							events.$emit('alert:open', {
								title: this.$t('popup_error.title'),
								message: this.$t('popup_error.message'),
							})
						}
					})
					.finally(() => {
						this.isLoading = false
					})
			},
			addRow() {
				let lastItem = this.invoice.items.slice(-1).pop()

				this.invoice.items.push({
					id: Math.floor(Math.random() * 10000000),
					description: '',
					amount: 1,
					unit: lastItem?.unit || this.$t('in_editor.default_unit'),
					tax_rate: lastItem?.tax_rate || 21,
					price: 1,
				})

				this.$nextTick(() => this.$refs.duplicatorItemTitle.slice(-1).pop().focus())
			},
			removeRow(item) {
				if (this.invoice.items.length > 1)
					this.invoice.items = this.invoice.items.filter(obj => obj.id !== item.id)
			}
		},
		mounted() {
			axios.get('/api/v1/invoicing/editor')
				.then(response => {
					this.isVatPayer = response.data.isVatPayer
				})

			axios.get(`/api/v1/invoicing/invoices/${this.$route.params.id}`)
				.then(response => {
					this.invoice.invoice_number = response.data.data.attributes.invoice_number
					this.invoice.variable_number = response.data.data.attributes.variable_number
					this.invoice.invoice_type = response.data.data.attributes.invoice_type
					this.invoice.delivery_at = response.data.data.attributes.delivery_at
					this.invoice.items = response.data.data.attributes.items
					this.invoice.discount_type = response.data.data.attributes.discount_type
					this.invoice.discount_rate = response.data.data.attributes.discount_rate

					this.client = response.data.data.attributes.client

					if (this.invoice.discount_type && this.invoice.discount_rate) {
						this.isDiscount = true
					}
				})
				.finally(() => {
					this.isLoadingPage = false
				})
		}
	}
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';
	@import '@assets/vuefilemanager/_forms';

	.total-wrapper {
		display: none;
	}

	#page-content {
		max-width: 1100px;
	}

	.content-page {
		display: grid;
		grid-template-columns: 2fr 1fr;
		margin-bottom: 30px;
		gap: 50px;

		.form {
			max-width: 100%;
		}
	}

	.summary-list {
		box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.06);
		border-radius: 8px;
		min-width: 300px;
		position: sticky;
		padding: 25px;
		top: 85px;

		&.is-error {
			border: 2px solid $danger;
			box-shadow: 0 7px 20px 5px rgba($danger, 0.06);
		}

		.error-message {
			font-weight: 600;
		}

		.next-submit {
			width: 100%;
			margin-top: 20px;
		}

		.disclaimer {
			@include font-size(12);
			line-height: 1.6;
			display: block;
			margin-top: 12px;
		}

		.is-offset {
			border-top: 1px solid $light_mode_border;
			display: block;
			padding-top: 10px;
		}

		.row {
			display: flex;
			justify-content: space-between;
			padding: 15px 0;

			&.small {
				padding: 0 0 10px;
			}

			&:first-child {
				padding-top: 0;
			}

			&.row-summary {
				//border-top: 1px solid $light_mode_border;
				padding-bottom: 0;

				b {
					font-weight: 800;
				}
			}
		}

		.cell {
			b {
				display: block;
				@include font-size(18);
			}

			small {
				color: $text-muted;
				@include font-size(12);
			}

			span {
				@include font-size(14);
				font-weight: 600;
			}
		}
	}

	.duplicator {

		.wrapper-inline {
			margin-bottom: 0 !important;
		}
	}

	@media only screen and (max-width: 970px) {
		.content-page {
			grid-template-columns: 1fr;
			margin-bottom: 80px;
			gap: 0;
		}

		.total-wrapper {
			box-shadow: 0 0 20px 5px hsla(220, 36%, 16%, 0.06);
			border-top-left-radius: 8px;
			border-top-right-radius: 8px;
			display: flex;
			justify-content: space-between;
			padding: 15px;
			position: fixed;
			bottom: 0;
			left: 0;
			right: 0;
			z-index: 9;
			background: white;

			b {
				@include font-size(14);
			}
		}
	}

	@media (prefers-color-scheme: dark) {
		.total-wrapper {
			background: $dark_mode_foreground;
			box-shadow: 0 0 20px 5px hsla(0, 0%, 0%, 0.25);
		}

		.summary-list {
			background: $dark_mode_foreground;

			.delete-dark {
				background: lighten($dark_mode_foreground, 5%);
			}

			.is-offset {
				border-top: 1px solid lighten($dark_mode_foreground, 5%);
			}

			.row {

				&.row-summary {
					border-top: 1px solid lighten($dark_mode_foreground, 5%);
				}
			}
		}
	}
</style>
