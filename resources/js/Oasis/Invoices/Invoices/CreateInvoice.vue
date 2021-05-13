<template>
	<div id="single-page">
		<MobileHeader :title="pageTitle" />
		<PageHeader :title="pageTitle" :can-back="true" />

		<div id="page-content">
			<div class="content-page" v-if="! isLoadingPage">
				<ValidationObserver @submit.prevent="createInvoice" ref="createInvoice" v-slot="{ invalid }" tag="form" class="form block-form">
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
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.client" :options="clients" :placeholder="$t('in_editor.plac.select_client')" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.ico') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_ico" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_ico" :placeholder="$t('in_editor.plac.client_ico')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<small v-if="fullDetails" class="input-help">
										{{ fullDetails }}
									</small>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.dic') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_dic" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_dic" :placeholder="$t('in_editor.plac.client_dic')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.ic_dph') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_ic_dph" v-slot="{ errors }">
									<input v-model="invoice.client_ic_dph" :placeholder="$t('in_editor.plac.client_ic_dph')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.company_name') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_name" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_name" :placeholder="$t('in_editor.plac.client_company')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.client_address') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_address" rules="required" v-slot="{ errors }">
									<input v-model="invoice.client_address" :placeholder="$t('in_editor.plac.client_address')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="wrapper-inline">
								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_city') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_city" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_city" :placeholder="$t('in_editor.plac.client_city')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_postal_code') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_postal_code" rules="required" v-slot="{ errors }">
										<input v-model="invoice.client_postal_code" :placeholder="$t('in_editor.plac.client_postal_code')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.client_country') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_country" rules="required" v-slot="{ errors }">
									<SelectInput v-model="invoice.client_country" :default="invoice.client_country" :options="countries" :placeholder="$t('in_editor.plac.client_country')" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.client_phone') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_phone_number" v-slot="{ errors }">
									<input v-model="invoice.client_phone_number" :placeholder="$t('in_editor.plac.client_phone')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.client_email') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_email" v-slot="{ errors }">
									<input v-model="invoice.client_email" :placeholder="$t('in_editor.plac.client_email')" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div v-if="isNewClient" class="block-wrapper">
								<label>{{ $t('in_editor.client_logo') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="client_avatar" v-slot="{ errors }">
									<ImageInput v-model="invoice.client_avatar" :error="errors[0]" />
								</ValidationProvider>
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
												<input v-model.number="item.amount" :placeholder="$t('in_editor.plac.item_amount')" type="number" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div v-if="isVatPayer" class="block-wrapper">
											<label>{{ $t('in_editor.tax_rate') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="tax_rate" rules="required" v-slot="{ errors }">
												<input v-model.number="item.tax_rate" :placeholder="$t('in_editor.plac.item_tax_rate')" type="number" step="1" min="1" max="100" :class="{'is-error': errors[0]}" class="focus-border-theme" />
												<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
											</ValidationProvider>
										</div>

										<div class="block-wrapper">
											<label>{{ $t('in_editor.price') }}:</label>
											<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="price" rules="required" v-slot="{ errors }">
												<input v-model.number="item.price" :placeholder="$t('in_editor.plac.item_price')" type="text" pattern="[0-9]{1,4}(\.[0-9]{2})?" step="0.01" :class="{'is-error': errors[0]}" class="focus-border-theme" />
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

							<div v-if="isNewClient && invoice.client_email" class="block-wrapper">
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

						<ButtonBase :disabled="isLoading" :loading="isLoading" @click.native="createInvoice" button-style="theme-solid" class="next-submit">
							{{ $t('in_editor.submit') }}
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
					'regular-invoice': this.$t('in_editor.page.create_regular_invoice'),
					'advance-invoice': this.$t('in_editor.page.create_advance_invoice'),
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
		watch: {
			isDiscount(val) {
				if (!val) {
					this.invoice.discount_rate = 10
					this.invoice.discount_type = 'percent'
				}
			},
			'invoice.invoice_number': function (val) {
				this.invoice.variable_number = val
			},
			'invoice.client_ico': function (val) {
				this.getCompanyDetails(val)
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
					discount_type: 'percent',
					discount_rate: 10,
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
			getCompanyDetails: debounce(function (value) {
				axios.get('/api/oasis/admin/company-details?ico=' + value)
					.then(response => {
						this.invoice.client_name = response.data.name
						this.invoice.client_address = response.data.addr_streetnr
						this.invoice.client_city = response.data.city
						this.invoice.client_postal_code = response.data.addr_zip
						this.fullDetails = response.data.name + ' ' + response.data.addr_full
					})
			}, 300),
			formatCurrency(value) {
				return new Intl
					.NumberFormat('cs-CS', {
						style: 'currency',
						currency: 'CZK'
					})
					.format(value)
			},
			formatNumber(value) {
				return (Math.round(value * 100) / 100)
					.toFixed(2);
			},
			async createInvoice() {
				const isValid = await this.$refs.createInvoice.validate();

				if (!isValid) {
					this.isError = true
					this.errorMessage = this.$t('in_editor.error')
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
						if (this.invoice[key])
							formData.append(key, this.invoice[key])
					}
				})

				// Send request to get user token
				axios
					.post('/api/invoices', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						}
					})
					.then(response => {

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('in_toaster.success_creation'),
						})

						// Reload invoices and go to invoice page
						this.$store.dispatch({
							'regular-invoice': 'getRegularInvoices',
							'advance-invoice': 'getAdvanceInvoices',
						}[this.invoice.invoice_type])

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
					tax_rate: lastTaxRate?.tax_rate || 21,
					price: 1,
				})

				this.$nextTick(() => this.$refs.duplicatorItemTitle.slice(-1).pop().focus())
			},
			removeRow(item) {
				if (this.invoice.items.length > 1)
					this.invoice.items = this.invoice.items.filter(obj => obj.id !== item.id)
			},
			get_recommended_delivery_date() {
				let now = new Date(),
					delivery_time = now.setDate(now.getDate() + 2 * 7)

				let year = new Intl.DateTimeFormat('en', {year: 'numeric'}).format(delivery_time),
					month = new Intl.DateTimeFormat('en', {month: '2-digit'}).format(delivery_time),
					day = new Intl.DateTimeFormat('en', {day: 'numeric'}).format(delivery_time)

				this.invoice.delivery_at = `${year}-${month}-${day}`
			}
		},
		mounted() {
			this.invoice.invoice_type = this.$route.query.type

			axios.get('/api/invoices/editor')
				.then(response => {
					this.isVatPayer = response.data.isVatPayer
					this.clients = response.data.clients

					this.clients.unshift({
						label: this.$t('in_editor.new_client'),
						value: 'new-client'
					})

					this.invoice.invoice_number = response.data.recommendedInvoiceNumber
					this.latestInvoiceNumber = response.data.latestInvoiceNumber

					this.get_recommended_delivery_date()
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

	.duplicator {

		.wrapper-inline {
			margin-bottom: 0 !important;
		}
	}

	@media only screen and (max-width: 970px) {
		.content-page {
			grid-template-columns: 1fr;
			margin-bottom: 30px;
			gap: 0;
		}
	}
</style>
