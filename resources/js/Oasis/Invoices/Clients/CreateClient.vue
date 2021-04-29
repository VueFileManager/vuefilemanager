<template>
	<div id="single-page">
		<MobileHeader :title="$router.currentRoute.meta.title" />
		<PageHeader :title="$router.currentRoute.meta.title" />

		<div id="page-content" class="medium-width">
			<div class="content-page">
				<ValidationObserver @submit.prevent="createClient" ref="createClient" v-slot="{ invalid }" tag="form" class="form block-form">
					<PageTab>
						 <PageTabGroup>
							<FormLabel>Company & Logo</FormLabel>

							<div class="block-wrapper">
								<label>Logo (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="avatar" v-slot="{ errors }">
									<ImageInput v-model="client.avatar" :error="errors[0]" />
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Company name:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="name" rules="required" v-slot="{ errors }">
									<input v-model="client.name" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</PageTabGroup>
						 <PageTabGroup>
							<FormLabel>Company Details</FormLabel>

							<div class="block-wrapper">
								<label>ICO:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
									<input v-model="client.ico" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>DIC (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" v-slot="{ errors }">
									<input v-model="client.dic" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>IC DPH (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" v-slot="{ errors }">
									<input v-model="client.ic_dph" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						 </PageTabGroup>
						 <PageTabGroup>
							<FormLabel>Company Address</FormLabel>

							<div class="block-wrapper">
								<label>Address:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
									<input v-model="client.address" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>City:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
									<input v-model="client.city" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Postal Code:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
									<input v-model="client.postal_code" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Country:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
									<SelectInput v-model="client.country" :default="client.country" :options="countries" placeholder="" :isError="errors[0]" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						 </PageTabGroup>
						 <PageTabGroup>
							<FormLabel>Contact Informations</FormLabel>

							<div class="block-wrapper">
								<label>Phone (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone_number" v-slot="{ errors }">
									<input v-model="client.phone_number" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Email (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
									<input v-model="client.email" placeholder="" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<InfoBox v-if="isError" type="error">
								<p>We found some issues in your form. Please check it out and submit again</p>
							</InfoBox>

							 <div class="block-wrapper">
								 <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
									Create Client
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
	import ThemeLabel from '@/components/Others/ThemeLabel'
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
				isLoading: false,
				isError: false,
				_client: {
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
				client: {
					avatar: undefined,
					name: 'MakingCG s.r.o.',
					email: 'info@makingcg.com',
					phone_number: '+421950123456',
					address: 'Karpatske namestie',
					city: 'Bratislava',
					postal_code: '04001',
					country: 'SK',
					ico: '4153514345',
					dic: '5346542865',
					ic_dph: 'SK200543253553',
				},
			}
		},
		methods: {
			async createClient() {

				const isValid = await this.$refs.createClient.validate();

				if (!isValid) {
					this.isError = true
					return
				};

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
					.post('/api/oasis/clients', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						}
					})
					.then(response => {

						events.$emit('toaster', {
							type: 'success',
							message: 'Client was created successfully',
						})

						// Go to User page
						this.$router.push({name: 'ClientDetail', params: {id: response.data.id}})
					})
					.catch(error => {
						this.isError = true

						if (error.response.status === 422) {

							Object.keys(error.response.data.errors).forEach(key => {

								let obj = {}; obj[key] = error.response.data.errors[key]

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

	.block-form {
		max-width: 100%;
	}

</style>
