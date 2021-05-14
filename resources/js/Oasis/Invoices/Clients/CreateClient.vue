<template>
	<div id="single-page">
		<MobileHeader :title="$t($router.currentRoute.meta.title)" />
		<PageHeader :title="$t($router.currentRoute.meta.title)" :can-back="true" />

		<div id="page-content" class="medium-width">
			<div class="content-page">
				<ValidationObserver @submit.prevent="createClient" ref="createClient" v-slot="{ invalid }" tag="form" class="form block-form">
					<PageTab>
						 <PageTabGroup>
							<FormLabel>{{ $t('in.form.company_and_logo') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_logo') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="avatar" v-slot="{ errors }">
									<ImageInput v-model="client.avatar" :error="errors[0]" />
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.company_name') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="name" rules="required" v-slot="{ errors }">
									<input v-model="client.name" :placeholder="$t('in_editor.plac.client_company')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>
						 <PageTabGroup>
							<FormLabel>{{ $t('in.form.company_details') }}</FormLabel>

							 <div class="wrapper-inline">
								<div class="block-wrapper">
									<label>{{ $t('in_editor.ico') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
										<input v-model="client.ico" :placeholder="$t('in_editor.plac.client_ico')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>{{ $t('in_editor.dic') }} ({{ $t('global.optional') }}):</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" v-slot="{ errors }">
										<input v-model="client.dic" :placeholder="$t('in_editor.plac.client_dic')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							 </div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.ic_dph') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" v-slot="{ errors }">
									<input v-model="client.ic_dph" :placeholder="$t('in_editor.plac.client_ic_dph')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						 </PageTabGroup>
						 <PageTabGroup>
							<FormLabel>{{ $t('in.form.company_address') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_address') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
									<input v-model="client.address" :placeholder="$t('in_editor.plac.client_address')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							 <div class="wrapper-inline">
								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_city') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
										<input v-model="client.city" :placeholder="$t('in_editor.plac.client_city')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_postal_code') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
										<input v-model="client.postal_code" :placeholder="$t('in_editor.plac.client_postal_code')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							 </div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_country') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
									<SelectInput v-model="client.country" :default="client.country" :options="countries" :placeholder="$t('in_editor.plac.client_country')" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						 </PageTabGroup>
						 <PageTabGroup>
							<FormLabel>{{ $t('in.form.contact_info') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_phone') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone_number" v-slot="{ errors }">
									<input v-model="client.phone_number" :placeholder="$t('in_editor.plac.client_phone')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_email') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
									<input v-model="client.email" :placeholder="$t('in_editor.plac.client_email')" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<InfoBox v-if="isError" type="error">
								<p>{{ $t('in.form.some_issues') }}</p>
							</InfoBox>

							 <div class="block-wrapper">
								 <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="submit-button">
									{{ $t('in.form.create_client') }}
								</ButtonBase>
							 </div>
						 </PageTabGroup>
					</PageTab>
				</ValidationObserver>
			</div>
		</div>
	</div>

</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
	import SelectInput from '@/components/Others/Forms/SelectInput'
	import ImageInput from '@/components/Others/Forms/ImageInput'
	import FormLabel from '@/components/Others/Forms/FormLabel'
	import MobileHeader from '@/components/Mobile/MobileHeader'
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import PageTab from '@/components/Others/Layout/PageTab'
	import PageHeader from '@/components/Others/PageHeader'
	import InfoBox from '@/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"
	import axios from "axios"

	export default {
		name: 'CreateClient',
		components: {
			ValidationProvider,
			ValidationObserver,
			PageTabGroup,
			MobileHeader,
			SelectInput,
			ImageInput,
			PageHeader,
			ButtonBase,
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
				isLoading: false,
				isError: false,
				client: {
					avatar: undefined,
					name: undefined,
					email: undefined,
					phone_number: undefined,
					address: undefined,
					city: undefined,
					postal_code: undefined,
					country: undefined,
					ico: undefined,
					dic: undefined,
					ic_dph: undefined,
				},
			}
		},
		methods: {
			async createClient() {

				const isValid = await this.$refs.createClient.validate();

				if (!isValid) {
					this.isError = true
					return
				}
				;

				// Start loading
				this.isLoading = true

				// Create form
				let formData = new FormData()

				// Append data to form
				Object.keys(this.client).forEach(key => {
					formData.append(key, this.client[key])
				})

				// Send request to get user token
				axios
					.post('/api/v1/invoicing/clients', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						}
					})
					.then(response => {

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('in_toaster.success_client_creation'),
						})

						// Go to User page
						this.$router.push({name: 'ClientDetail', params: {id: response.data.id}})
					})
					.catch(error => {
						this.isError = true

						if (error.response.status === 422) {

							Object.keys(error.response.data.errors).forEach(key => {

								let obj = {};
								obj[key] = error.response.data.errors[key]

								this.$refs.createClient.setErrors(obj);
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
			}
		},
	}
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';
	@import '@assets/vuefilemanager/_forms';

	@media only screen and (max-width: 960px) {

		.submit-button {
			width: 100%;
			margin-top: 30px;
		}
	}
</style>
