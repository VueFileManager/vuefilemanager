<template>
	<div id="single-page">
		<MobileHeader :title="$router.currentRoute.meta.title" />
		<PageHeader :title="$router.currentRoute.meta.title" />

		<div v-if="!isLoading && !profileNotExist" id="page-content" class="medium-width">
			<div class="content-page">
				<PageTab>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>Company & Logo</FormLabel>
							<InfoBox>
								<p>Here you can set your <b class="text-theme">billing profile</b> which will be paste to your every newly generated invoice.</p>
							</InfoBox>

							<div class="block-wrapper">
								<label>Logo (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="logo" v-slot="{ errors }">
									<ImageInput @input="$updateImage('/oasis/invoices/profile', 'logo', profile.logo)" :image="$getImage(profile.logo)" v-model="profile.logo" :error="errors[0]" />
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Company name:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="company" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'company', profile.company)" v-model="profile.company" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Registration Notes (optional):</label>
								<div class="input-wrapper">
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="registration_notes" rules="required" v-slot="{ errors }">
                        			<textarea
										rows="2"
										@input="$updateText('/oasis/invoices/profile', 'registration_notes', profile.registration_notes)"
										v-model="profile.registration_notes"
										placeholder=""
										:class="{'is-error': errors[0]}"
										class="focus-border-theme"
									/>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
								</div>
							</div>

						</div>
					</PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>Company Details</FormLabel>

							<div class="block-wrapper">
								<label>ICO:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'ico', profile.ico)" v-model="profile.ico" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>DIC:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'dic', profile.dic)" v-model="profile.dic" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>IC DPH (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'ic_dph', profile.ic_dph)" v-model="profile.ic_dph" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>Company Address</FormLabel>

							<div class="block-wrapper">
								<label>Address:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'address', profile.address)" v-model="profile.address" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="wrapper-inline">
								<div class="block-wrapper">
									<label>City:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
										<input @input="$updateText('/oasis/invoices/profile', 'city', profile.city)" v-model="profile.city" placeholder="" type="text"
											   :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>Postal Code:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
										<input @input="$updateText('/oasis/invoices/profile', 'postal_code', profile.postal_code)" v-model="profile.postal_code" placeholder="" type="text"
											   :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							</div>

							<div class="block-wrapper">
								<label>Country:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
									<SelectInput @input="$updateText('/oasis/invoices/profile', 'country', profile.country)" v-model="profile.country" :default="profile.country" :options="countries" placeholder="" :isError="errors[0]"/>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>Bank Info</FormLabel>

							<div class="block-wrapper">
								<label>Bank Name:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="bank" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'bank', profile.bank)" v-model="profile.bank" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Iban:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="iban" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'iban', profile.iban)" v-model="profile.iban" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Swift code:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="swift" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'swift', profile.swift)" v-model="profile.swift" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>Author</FormLabel>

							<div class="block-wrapper">
								<label>Phone (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'phone', profile.phone)" v-model="profile.phone" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Email (optional):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'email', profile.email)" v-model="profile.email" placeholder="" type="email"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Author name:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="author" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/oasis/invoices/profile', 'author', profile.author)" v-model="profile.author" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>Stamp:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="stamp" v-slot="{ errors }">
									<ImageInput @input="$updateImage('/oasis/invoices/profile', 'stamp', profile.stamp)" :image="$getImage(profile.stamp)" v-model="profile.stamp" :error="errors[0]" />
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
				</PageTab>
			</div>
		</div>

		<EmptyPageContent
			v-if="!isLoading && profileNotExist"
			icon="edit"
			title="You don't have billing profile"
			description="Before your first invoice, please set up your billing profile."
		>
            <router-link :to="{name: 'BillingProfileSetUp'}" tag="p">
                <ButtonBase button-style="theme">
					Set up Billing Profile
				</ButtonBase>
            </router-link>
        </EmptyPageContent>
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
				isLoading: true,
				profile: undefined,
				profileNotExist: false,
			}
		},
		methods: {},
		created() {
			axios.get('/api/oasis/invoices/profile')
				.then(response => {
					this.profile = response.data.data.attributes
				})
				.catch(error => {
					if (error.response.status === 404) {
						this.profileNotExist = true
					}
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

	.block-form {
		max-width: 100%;
	}

</style>
