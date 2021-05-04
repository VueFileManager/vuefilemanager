<template>
	<div id="single-page">
		<MobileHeader :title="pageTitle" />
		<PageHeader :title="pageTitle" />

		<div id="page-content">
			<div class="content-page">
				<ValidationObserver @submit.prevent="createInvoice" ref="createInvoice" v-slot="{ invalid }" tag="form" class="form block-form">
					<PageTab>

						<!--Properties-->
						<PageTabGroup>
							<FormLabel icon="tool">Invoice Properties</FormLabel>

							<div class="block-wrapper">
								<label>Invoice Number:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="invoice_number" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.invoice_number" placeholder="" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<small v-if="latestInvoiceNumber" class="input-help">
										Recommendation based on your latest invoice number {{ latestInvoiceNumber }}
									</small>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Variable Number:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="variable_number" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.variable_number" placeholder="" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<small v-if="latestInvoiceNumber" class="input-help">
										Recommendation based on your invoice number
									</small>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Delivery At:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="variable_number" rules="required" v-slot="{ errors }">
									<input v-model="invoice.delivery_at" placeholder="" type="date" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>

						<!--Client-->
						<PageTabGroup>
							<FormLabel icon="user">Client</FormLabel>

							<div class="block-wrapper">
								<label>Client:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="invoice_type" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.client" :options="clients" placeholder="Create new or select existing client..." :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="wrapper-inline">
								<div class="block-wrapper">
									<label>ICO:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_ico" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_ico" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>DIC:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_dic" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_dic" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>IC DPH (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_ic_dph" v-slot="{ errors }">
									<input v-model="invoice.client_ic_dph" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Company name:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_name" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_name" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Address:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_address" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_address" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="wrapper-inline">
								<div class="block-wrapper">
									<label>City:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_city" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_city" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>Postal Code:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_postal_code" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_postal_code" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Country:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_country" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.client_country" :default="invoice.client_country" :options="countries" placeholder="" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Phone (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_phone_number" v-slot="{ errors }">
									<input v-model="invoice.client_phone_number" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Email (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_email" v-slot="{ errors }">
									<input v-model="invoice.client_email" placeholder="" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>Logo (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_avatar" v-slot="{ errors }">
									<ImageInput v-model="invoice.client_avatar" :error="errors[0]" />
								</ValidationProvider>
							</div>
						</PageTabGroup>

						<!--Items-->
						<PageTabGroup>
							<FormLabel icon="edit">Items</FormLabel>

							<div class="duplicator">
								<div class="plan-item duplicator-item" v-for="(item, index) in invoice.items" :key="index++">
									<x-icon @click="removeRow(item)" v-if="index !== 0" size="22" class="delete-item hover-text-theme" />

									<div class="block-wrapper">
										<label>Description:</label>
										<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="description" rules="required" v-slot="{ errors }">
											<input v-model="item.description" ref="duplicatorItemTitle" placeholder="Type item description..." type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
											<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
										</ValidationProvider>
									</div>

									<div class="wrapper-inline">
										<div class="block-wrapper">
											<label>Amount:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="amount" rules="required" v-slot="{ errors }">
												<input v-model.number="item.amount" placeholder="The amount in Pcs." type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div v-if="isVatPayer" class="block-wrapper">
											<label>Tax Rate:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="tax_rate" rules="required" v-slot="{ errors }">
												<input v-model.number="item.tax_rate" placeholder="Type item tax rate in %..." type="number" step="1" min="1" max="100" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div class="block-wrapper">
											<label>Price:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="price" rules="required" v-slot="{ errors }">
												<input v-model.number="item.price" placeholder="Type the item price..." type="number" step="0.01" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>
									</div>
								</div>

								<ButtonBase @click.native="addRow" class="duplicator-add-button" button-style="theme" style="width: 100%">
									Add New Item
								</ButtonBase>
							</div>
						</PageTabGroup>

						<!--Discount-->
						<PageTabGroup>
							<FormLabel icon="credit-card">Discount</FormLabel>

							<div class="block-wrapper">
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">Apply discount:</label>
											<small class="input-help">You can apply percentage or value discount on your invoice.</small>
										</div>
										<SwitchInput v-model="isDiscount" class="switch" :state="isDiscount" />
									</div>
								</ValidationProvider>
							</div>

							<div v-if="isDiscount" class="block-wrapper">
								<label>Discount Type:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="discount_type" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.discount_type" :default="invoice.discount_type" :options="discountTypeList" placeholder="" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isDiscount" class="block-wrapper">
								<label>Discount Rate:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="discount_rate" rules="required" v-slot="{ errors }">
									<input v-model.number="invoice.discount_rate" placeholder="" max="100" min="0" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>

						<!--Others-->
						<PageTabGroup>
							<FormLabel icon="settings">Others</FormLabel>

							<div v-if="isNewClient && invoice.client_email" class="block-wrapper">
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">Store client for future use:</label>
											<small class="input-help">Client will be stored to your list and will be ready to reuse again when you create new invoice.</small>
										</div>
										<SwitchInput v-model="invoice.send_invoice" class="switch" :state="invoice.send_invoice" />
									</div>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
									<div class="inline-wrapper">
										<div class="switch-label">
											<label class="input-label">Send invoice on client's email:</label>
											<small class="input-help">Invoice will be sent to client immediately after invoice generate.</small>
										</div>
										<SwitchInput v-model="invoice.send_invoice" class="switch" :state="invoice.send_invoice" />
									</div>
								</ValidationProvider>
							</div>
						</PageTabGroup>
					</PageTab>
				</ValidationObserver>
				<div class="summary">
					<FormLabel icon="credit-card">Invoice Summary</FormLabel>
					<div class="summary-list" :class="{'is-error': isError}">

						<div v-if="isDiscount && invoice.discount_rate > 0" class="row small">
							<div class="cell">
								<span>Discount</span>
							</div>
							<div class="cell">
								<span>-{{ invoice.discount_type === 'percent' ? formatNumber(invoice.discount_rate) + '%' : formatCurrency(invoice.discount_rate) }}</span>
							</div>
						</div>

						<div :class="{'is-offset': isDiscount && invoice.discount_rate > 0}">
							<div v-for="(tax, i) in taxBased" :key="i" class="row small">
								<div class="cell">
									<span>VAT Base {{ tax.rate }}%</span>
								</div>
								<div class="cell">
									<span>{{ formatCurrency(tax.total) }}</span>
								</div>
							</div>
						</div>

						<div :class="{'is-offset': taxSummary.length > 1}">
							<div v-for="(tax, i) in taxSummary" :key="i" class="row small">
								<div class="cell">
									<span>VAT {{ tax.rate }}%</span>
								</div>
								<div class="cell">
									<span>{{ formatCurrency(tax.total) }}</span>
								</div>
							</div>
						</div>

						<div class="row row-summary">
							<div class="cell">
								<b>Spolu</b>
							</div>
							<div class="cell">
								<b>{{ formatCurrency(total) }}</b>
							</div>
						</div>

						<ButtonBase :disabled="isLoading" :loading="isLoading" @click.native="createInvoice" button-style="theme-solid" class="next-submit">
							Store & Generate Invoice
						</ButtonBase>
						<p class="error-message" v-if="isError">
							{{ errorMessage }}
						</p>
					</div>
				</div>
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
	import {required} from 'vee-validate/dist/rules'
	import {XIcon} from 'vue-feather-icons'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"
	import axios from "axios"

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
					'regular-invoice': 'Create Regular Invoice',
					'advance-invoice': 'Create Advance Invoice',
				}[this.$route.query.type]
			},
			isNewClient() {
				return this.invoice.client === 'new-client'
			},
			taxBased() {
				let bag = [];

				this.invoice.items.forEach(item => {

					if (item.price && item.amount && item.tax_rate) {

						if (!bag.find(bagItem => bagItem.rate === item.tax_rate)) {

							bag.push({
								rate: item.tax_rate,
								total: (item.price * item.amount),
							})
						} else {
							bag.find(bagItem => {

								// Count total tax rate for percentage
								if (bagItem.rate === item.tax_rate) {
									bagItem.total += (item.price * item.amount)
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
								total: (item.price * item.amount) * (item.tax_rate / 100),
							})

						} else {

							bag.map(bagItem => {

								// Count total tax rate for percentage
								if (bagItem.rate === item.tax_rate) {
									bagItem.total += (item.price * item.amount) * (item.tax_rate / 100)
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

						let total_without_tax = (item.price * item.amount)

						// Count tax
						if (item.tax_rate) {
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
		watch: {
			isDiscount(val) {
				if (!val) {
					this.invoice.discount_rate = 10
					this.invoice.discount_type = 'percent'
				}
			},
			'invoice.invoice_number': function (val) {
				this.invoice.variable_number = val
			}
		},
		data() {
			return {
				isLoading: true,
				isError: false,
				isDiscount: true,
				isVatPayer: false,
				clients: [],
				latestInvoiceNumber: undefined,
				_invoice: {
					invoice_type: '',
					invoice_number: '',
					variable_number: '',
					delivery_at: '',
					items: [
						{
							id: 1,
							description: '',
							amount: 1,
							tax_rate: undefined,
							price: undefined,
						}
					],
					discount_type: null,
					discount_rate: null,
					client: '',
					client_avatar: '',
					client_name: '',
					client_email: '',
					client_phone_number: '',
					client_address: '',
					client_city: '',
					client_postal_code: '',
					client_country: '',
					client_ico: '',
					client_dic: '',
					client_ic_dph: '',
					send_invoice: true,
					store_client: true,
				},
				invoice: {
					invoice_type: 'regular-invoice',
					invoice_number: undefined,
					variable_number: undefined,
					delivery_at: '2021-04-09',
					items: [
						{
							id: 1,
							description: 'Item 1',
							amount: 2,
							tax_rate: 20,
							price: 200,
						},
						{
							id: 2,
							description: 'Item 2',
							amount: 1,
							tax_rate: 10,
							price: 100,
						},
					],
					discount_type: 'value',
					discount_rate: 10,
					client: '0354bab9-1b23-4d17-aa5f-fd8e9aaaf0a2',
					client_avatar: '',
					client_name: 'VueFileManager Inc.',
					client_email: 'howdy@hi5ve.digital',
					client_phone_number: '+421950123456',
					client_address: 'Does 20',
					client_city: 'Bratislava',
					client_postal_code: '04001',
					client_country: 'SK',
					client_ico: '46530045',
					client_dic: '2023489457',
					client_ic_dph: 'SK2023489457',
					send_invoice: false,
					store_client: true,
				},
				invoiceTypeList: [
					{
						label: 'Regular Invoice',
						value: 'regular-invoice',
					},
					{
						label: 'Advance Invoice',
						value: 'advance-invoice',
					},
				],
				discountTypeList: [
					{
						label: 'Percentage',
						value: 'percent',
					},
					{
						label: 'Amount',
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
						currency: 'CZK'
					})
					.format(value)
			},
			formatNumber(value) {
				return (Math.round(value * 100) / 100).toFixed(2);
			},
			async createInvoice() {

				const isValid = await this.$refs.createInvoice.validate();

				if (!isValid) {
					this.isError = true
					return
				}

				// Start loading
				this.isLoading = true

				// Create form
				let formData = new FormData()

				// Append data to form
				Object.keys(this.invoice).forEach(key => {

					if (key === 'items') {
						formData.append(key, JSON.stringify(this.invoice[key]))
					} else {
						formData.append(key, this.invoice[key])
					}
				})

				// Send request to get user token
				axios
					.post('/api/oasis/invoices', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						}
					})
					.then(response => {

						events.$emit('toaster', {
							type: 'success',
							message: 'Invoice was created successfully',
						})

						// Go to invoice page
						this.$router.push({name: 'InvoicesList'})
					})
					.catch(error => {
						this.isError = true

						if (error.response.status === 422) {

							Object.keys(error.response.data.errors).forEach(key => {

								let obj = {};
								obj[key] = error.response.data.errors[key]

								this.$refs.createInvoice.setErrors(obj);
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
				let lastTaxRate = this.invoice.items.slice(-1).pop()

				this.invoice.items.push({
					id: Math.floor(Math.random() * 10000000),
					description: '',
					amount: 1,
					tax_rate: lastTaxRate?.tax_rate || 0,
					price: 1,
				})

				this.$nextTick(() => this.$refs.duplicatorItemTitle.slice(-1).pop().focus())

			},
			removeRow(item) {
				this.invoice.items = this.invoice.items.filter(obj => obj.id !== item.id)
			},
		},
		mounted() {
			this.invoice.invoice_type = this.$route.query.type

			axios.get('/api/oasis/invoices/editor')
				.then(response => {
					this.isVatPayer = response.data.isVatPayer
					this.clients = response.data.clients

					this.clients.unshift({
						label: 'Register new client...',
						value: 'new-client'
					})

					this.invoice.invoice_number = response.data.recommendedInvoiceNumber
					this.latestInvoiceNumber = response.data.latestInvoiceNumber
				})
				.finally(() => {
					this.isLoading = false
				})
		}
	}
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';
	@import '@assets/vuefilemanager/_forms';

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
				border-top: 1px solid $light_mode_border;
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
</style>
