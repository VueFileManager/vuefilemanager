<template>
	<div id="single-page">
		<MobileHeader :title="$t($router.currentRoute.meta.title)" />
		<PageHeader :title="$t($router.currentRoute.meta.title)" />

		<div v-if="!isLoading && !profileNotExist" id="page-content" class="medium-width">
			<div class="content-page">
				<PageTab>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>{{ $t('in.form.company_and_logo') }}</FormLabel>
							<InfoBox>
								<p v-html="$t('in.bill_profile_note')"></p>
							</InfoBox>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_logo') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="logo" v-slot="{ errors }">
									<ImageInput @input="$updateImage('/invoices/profile', 'logo', profile.logo)" :image="$getImage(profile.logo)" v-model="profile.logo" :error="errors[0]" />
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.company_name') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="company" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'company', profile.company)" v-model="profile.company" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.reg_notes') }} ({{ $t('global.optional') }}):</label>
								<div class="input-wrapper">
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="registration_notes" rules="required" v-slot="{ errors }">
                        			<textarea
										rows="2"
										@input="$updateText('/invoices/profile', 'registration_notes', profile.registration_notes)"
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
							<FormLabel>{{ $t('in.form.company_details') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.ico') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'ico', profile.ico)" v-model="profile.ico" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.dic') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'dic', profile.dic)" v-model="profile.dic" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.ic_dph') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'ic_dph', profile.ic_dph)" v-model="profile.ic_dph" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>{{ $t('in.form.company_address') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_address') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'address', profile.address)" v-model="profile.address" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="wrapper-inline">
								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_city') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
										<input @input="$updateText('/invoices/profile', 'city', profile.city)" v-model="profile.city" placeholder="" type="text"
											   :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>

								<div class="block-wrapper">
									<label>{{ $t('in_editor.client_postal_code') }}:</label>
									<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
										<input @input="$updateText('/invoices/profile', 'postal_code', profile.postal_code)" v-model="profile.postal_code" placeholder="" type="text"
											   :class="{'is-error': errors[0]}" class="focus-border-theme" />
										<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
									</ValidationProvider>
								</div>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_country') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
									<SelectInput @input="$updateText('/invoices/profile', 'country', profile.country)" v-model="profile.country" :default="profile.country" :options="countries" placeholder="" :isError="errors[0]"/>
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>{{ $t('in.form.bank_info') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in.form.bank_name') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="bank" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'bank', profile.bank)" v-model="profile.bank" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.iban') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="iban" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'iban', profile.iban)" v-model="profile.iban" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.swift_code') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="swift" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'swift', profile.swift)" v-model="profile.swift" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>
						</div>
					 </PageTabGroup>
					 <PageTabGroup>
						<div class="form block-form">
							<FormLabel>{{ $t('in.form.author') }}</FormLabel>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_phone') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'phone', profile.phone)" v-model="profile.phone" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in_editor.client_email') }} ({{ $t('global.optional') }}):</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'email', profile.email)" v-model="profile.email" placeholder="" type="email"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.author_name') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="author" rules="required" v-slot="{ errors }">
									<input @input="$updateText('/invoices/profile', 'author', profile.author)" v-model="profile.author" placeholder="" type="text"
										   :class="{'is-error': errors[0]}" class="focus-border-theme" />
									<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
								</ValidationProvider>
							</div>

							<div class="block-wrapper">
								<label>{{ $t('in.form.stamp') }}:</label>
								<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="stamp" v-slot="{ errors }">
									<ImageInput @input="$updateImage('/invoices/profile', 'stamp', profile.stamp)" :image="$getImage(profile.stamp)" v-model="profile.stamp" :error="errors[0]" />
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
			:title="$t('in.empty.bill_profile_title')"
			:description="$t('in.empty.bill_profile_description')"
		>
            <router-link :to="{name: 'BillingProfileSetUp'}" tag="p">
                <ButtonBase button-style="theme">
					{{ $t('in.button.setup_bill_profile') }}
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
			axios.get('/api/invoices/profile')
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
