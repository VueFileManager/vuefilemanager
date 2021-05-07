<template>
	<div id="single-page">
		<MobileHeader :title="$t($router.currentRoute.meta.title)" />
		<PageHeader :can-back="true" :title="$t($router.currentRoute.meta.title)" />
		<div id="page-content" class="medium-width">
			<ValidationObserver @submit.prevent="createBillingProfile" ref="createBillingProfile" v-slot="{ invalid }" tag="div" class="content-page form block-form">
				<FormLabel>{{ $t('in.form.company_and_logo') }}</FormLabel>
				<InfoBox>
					<p v-html="$t('in.bill_profile_note')"></p>
				</InfoBox>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.client_logo') }} ({{ $t('global.optional') }}):</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="logo" v-slot="{ errors }">
						<ImageInput :image="$getImage(profile.logo)" v-model="profile.logo" :error="errors[0]" />
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.company_name') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="company" rules="required" v-slot="{ errors }">
						<input v-model="profile.company" placeholder="Type your company name..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.reg_notes') }} ({{ $t('global.optional') }}):</label>
					<div class="input-wrapper">
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="registration_notes" v-slot="{ errors }">
							<textarea
								rows="2"
								v-model="profile.registration_notes"
								placeholder="Type your company registration notes..."
								:class="{'is-error': errors[0]}"
								class="focus-border-theme"
							/>
							<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
						</ValidationProvider>
					</div>
				</div>
				<FormLabel class="mt-70">{{ $t('in.form.company_details') }}</FormLabel>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.ico') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
						<input v-model="profile.ico" placeholder="Type your company ICO..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.dic') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" rules="required" v-slot="{ errors }">
						<input v-model="profile.dic" placeholder="Type your company DIC..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.ic_dph') }} ({{ $t('global.optional') }}):</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" v-slot="{ errors }">
						<input v-model="profile.ic_dph" placeholder="Type your company IC DPH..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<FormLabel class="mt-70">{{ $t('in.form.company_address') }}</FormLabel>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.client_address') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
						<input v-model="profile.address" placeholder="Type your company address..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="wrapper-inline">
					<div class="block-wrapper">
						<label>{{ $t('in_editor.client_city') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
							<input v-model="profile.city" placeholder="Type your company city..." type="text"
								   :class="{'is-error': errors[0]}" class="focus-border-theme" />
							<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
						</ValidationProvider>
					</div>
					<div class="block-wrapper">
						<label>{{ $t('in_editor.client_postal_code') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
							<input v-model="profile.postal_code" placeholder="Type your company postal code..." type="text"
								   :class="{'is-error': errors[0]}" class="focus-border-theme" />
							<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
						</ValidationProvider>
					</div>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.client_country') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
						<SelectInput v-model="profile.country" :default="profile.country" :options="countries" placeholder="Select your company country" :isError="errors[0]" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<FormLabel class="mt-70">{{ $t('in.form.bank_info') }}</FormLabel>
				<div class="block-wrapper">
					<label>{{ $t('in.form.bank_name') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="bank" rules="required" v-slot="{ errors }">
						<input v-model="profile.bank" placeholder="Type your bank name..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.iban') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="iban" rules="required" v-slot="{ errors }">
						<input v-model="profile.iban" placeholder="Type your IBAN..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.swift_code') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="swift" rules="required" v-slot="{ errors }">
						<input v-model="profile.swift" placeholder="Type your swift code..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<FormLabel class="mt-70">{{ $t('in.form.author') }}</FormLabel>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.client_phone') }} ({{ $t('global.optional') }}):</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone" v-slot="{ errors }">
						<input v-model="profile.phone" placeholder="Type your phone number..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in_editor.client_email') }} ({{ $t('global.optional') }}):</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
						<input v-model="profile.email" placeholder="Type your email..." type="email"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.author_name') }}:</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="author" rules="required" v-slot="{ errors }">
						<input v-model="profile.author" placeholder="Type the author invoice name..." type="text"
							   :class="{'is-error': errors[0]}" class="focus-border-theme" />
						<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
					</ValidationProvider>
				</div>
				<div class="block-wrapper">
					<label>{{ $t('in.form.stamp') }} ({{ $t('global.optional') }}):</label>
					<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="stamp" v-slot="{ errors }">
						<ImageInput v-model="profile.stamp" :error="errors[0]" />
					</ValidationProvider>
				</div>
				<InfoBox v-if="isError" type="error">
					<p>{{ $t('in.form.some_issues') }}</p>
				</InfoBox>
				<div class="block-wrapper">
					<ButtonBase @click.native="createBillingProfile" :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" style="margin-bottom: 35px">
						{{ $t('in.button.store_bill_profile') }}
					</ButtonBase>
				</div>
			</ValidationObserver>
		</div>
	</div>
</template>

<script>
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
	import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
	import SelectInput from '@/components/Others/Forms/SelectInput'
	import ImageInput from '@/components/Others/Forms/ImageInput'
	import FormLabel from '@/components/Others/Forms/FormLabel'
	import MobileHeader from '@/components/Mobile/MobileHeader'
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import PageTab from '@/components/Others/Layout/PageTab'
	import PageHeader from '@/components/Others/PageHeader'
	import ThemeLabel from '@/components/Others/ThemeLabel'
	import InfoBox from '@/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"
	import axios from "axios"

	export default {
		name: 'BillingProfile',
		props: [
			'user'
		],
		components: {
			ValidationProvider,
			ValidationObserver,
			EmptyPageContent,
			PageTabGroup,
			MobileHeader,
			SelectInput,
			ImageInput,
			PageHeader,
			ButtonBase,
			ThemeLabel,
			FormLabel,
			required,
			InfoBox,
			PageTab,
		},
		computed: {
			...mapGetters([
				'countries',
				'config',
			]),
		},
		data() {
			return {
				isError: false,
				isLoading: false,
				profile: {
					logo: undefined,
					company: undefined,
					registration_notes: undefined,
					ico: undefined,
					dic: undefined,
					ic_dph: undefined,
					address: undefined,
					city: undefined,
					postal_code: undefined,
					country: undefined,
					bank: undefined,
					iban: undefined,
					swift: undefined,
					phone: undefined,
					email: undefined,
					author: undefined,
					stamp: undefined,
				},
			}
		},
		methods: {
			async createBillingProfile() {
				const isValid = await this.$refs.createBillingProfile.validate();

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
				Object.keys(this.profile).forEach(key => {

					if (this.profile[key])
						formData.append(key, this.profile[key])
				})

				// Send request to get user token
				axios
					.post('/api/oasis/invoices/profile', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						}
					})
					.then(response => {

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('in.toaster.success_bill_profile'),
						})

						this.$store.dispatch('getAppData')
						this.$router.push({name: 'BillingProfile'})
					})
					.catch(error => {
						this.isError = true

						if (error.response.status === 422) {

							Object.keys(error.response.data.errors).forEach(key => {

								let obj = {};
								obj[key] = error.response.data.errors[key]

								this.$refs.createBillingProfile.setErrors(obj);
							})

						} else {
							events.$emit('alert:open', {
								title: this.$t('popup_error.title'),
								message: this.$t('popup_error.message'),
							})
						}
					})
					.finally(() => this.isLoading = false)
			},
		},
	}
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';
	@import '@assets/vuefilemanager/_forms';

	.block-form {
		max-width: 100%;
	}

</style>
