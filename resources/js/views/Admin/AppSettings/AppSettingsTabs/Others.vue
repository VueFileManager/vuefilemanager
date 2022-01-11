<template>
    <PageTab :is-loading="isLoading">

		<!--Store & Upload-->
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('Storage & Upload') }}
			</FormLabel>

			<!--Available only when is not metered billing-->
			<div v-if="config.subscriptionType !== 'metered'">
				<AppInputSwitch :title="$t('admin_settings.others.storage_limit')" :description="$t('admin_settings.others.storage_limit_help')">
					<SwitchInput
						@input="$updateText('/admin/settings', 'storage_limitation', app.storageLimitation)"
						v-model="app.storageLimitation"
						:state="app.storageLimitation"
						class="switch"
					/>
				</AppInputSwitch>

				<AppInputText v-if="app.storageLimitation" :title="$t('admin_settings.others.default_storage')">
					<input @input="$updateText('/admin/settings', 'default_max_storage_amount', app.defaultStorage)"
						   v-model="app.defaultStorage"
						   min="1"
						   max="999999999"
						   :placeholder="$t('admin_settings.others.default_storage_plac')"
						   type="number"
						   class="focus-border-theme input-dark"
					/>
				</AppInputText>
			</div>

			<AppInputText :title="$t('admin_settings.others.upload_limit')" :description="$t('admin_settings.others.upload_limit_help')">
				<input @input="$updateText('/admin/settings', 'upload_limit', app.uploadLimit, true)" v-model="app.uploadLimit" :placeholder="$t('admin_settings.others.upload_limit_plac')" type="number" min="0" step="1" class="focus-border-theme input-dark" />
			</AppInputText>

			<AppInputText :title="$t('admin_settings.others.mimetypes_blacklist')" :description="$t('admin_settings.others.mimetypes_blacklist_help')" :is-last="true">
				<textarea rows="2" @input="$updateText('/admin/settings', 'mimetypes_blacklist', app.mimetypesBlacklist, true)" v-model="app.mimetypesBlacklist" :placeholder="$t('admin_settings.others.mimetypes_blacklist_plac')" type="text" class="focus-border-theme input-dark" />
			</AppInputText>
		</div>

		<!--Other Settings-->
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('Application') }}
			</FormLabel>

			<AppInputButton :title="$t('Cache')" :description="$t('Did you change anything in your .env file? Then clear your cache.')">
				<ButtonBase @click.native="flushCache" :loading="isFlushingCache" :disabled="isFlushingCache" class="sm:w-auto w-full" button-style="theme">
					{{ $t('admin_settings.others.cache_clear') }}
				</ButtonBase>
			</AppInputButton>

			<AppInputText :title="$t('admin_settings.others.contact_email')">
				<input class="focus-border-theme input-dark" @input="$updateText('/admin/settings', 'contact_email', app.contactMail)" v-model="app.contactMail" :placeholder="$t('admin_settings.others.contact_email_plac')" type="email" />
			</AppInputText>

			<AppInputText :title="$t('admin_settings.others.google_analytics')" :is-last="true">
				<input @input="$updateText('/admin/settings', 'google_analytics', app.googleAnalytics, true)" v-model="app.googleAnalytics" :placeholder="$t('admin_settings.others.google_analytics_plac')" type="text" class="focus-border-theme input-dark" />
			</AppInputText>
		</div>

		<!--User Login/Registration-->
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('User Login/Registration') }}
			</FormLabel>

			<AppInputSwitch :title="$t('admin_settings.others.allow_registration')" :description="$t('admin_settings.others.allow_registration_help')">
				<SwitchInput
					@input="$updateText('/admin/settings', 'registration', app.userRegistration)"
					v-model="app.userRegistration"
					class="switch"
					:state="app.userRegistration"
				/>
			</AppInputSwitch>

			<AppInputSwitch :title="$t('Require Email Verification')" :description="$t('admin_settings.others.allow_user_verification_help')" :is-last="true">
				<SwitchInput
					@input="$updateText('/admin/settings', 'user_verification', app.userVerification)"
					v-model="app.userVerification"
					class="switch"
					:state="app.userVerification"
				/>
			</AppInputSwitch>
		</div>

		<!--Facebook Social Authentication-->
		<div class="card shadow-card">
			<img :src="$getSocialLogo('facebook')" alt="Facebook" class="mb-8 h-5">

			<AppInputSwitch :title="$t('Allow Login via Facebook')" :description="$t('You users will be able to login via Facebook account.')" :is-last="! facebook.allowedService">
				<SwitchInput
					@input="$updateText('/admin/settings', 'allowed_facebook_login', facebook.allowedService)"
					v-model="facebook.allowedService"
					class="switch"
					:state="facebook.allowedService"
				/>
			</AppInputSwitch>

			<div v-if="config.isFacebookLoginConfigured && facebook.allowedService" @click="facebook.isVisibleCredentialsForm = !facebook.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': facebook.isVisibleCredentialsForm}">
				<edit2-icon size="12" class="vue-feather text-theme mr-2" />
				<b class="text-xs">{{ $t('Update Your Credentials') }}</b>
			</div>

			<!--Set up facebook credentials-->
			<ValidationObserver
				v-if="(! config.isFacebookLoginConfigured || facebook.isVisibleCredentialsForm) && facebook.allowedService"
				@submit.prevent="storeCredentials('facebook')"
				ref="credentialsForm"
				v-slot="{ invalid }"
				tag="form"
				class="p-5 shadow-lg rounded-xl"
			>
				<FormLabel v-if="! config.isFacebookLoginConfigured" icon="shield">
					{{ $t('Configure Credentials') }}
				</FormLabel>

				<ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client ID')" :error="errors[0]">
						<input v-model="facebook.credentials.client_id" :placeholder="$t('Paste your Client ID here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client Secret')" :error="errors[0]">
						<input v-model="facebook.credentials.client_secret" :placeholder="$t('Paste your Client Secret here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
					{{ $t('Store Credentials') }}
				</ButtonBase>
			</ValidationObserver>

		</div>

		<!--Google Social Authentication-->
		<div class="card shadow-card">
			<img :src="$getSocialLogo('google')" alt="Google" class="mb-8 h-7">

			<AppInputSwitch :title="$t('Allow Login via Google')" :description="$t('You users will be able to login via Google account.')" :is-last="! google.allowedService">
				<SwitchInput
					@input="$updateText('/admin/settings', 'allowed_google_login', google.allowedService)"
					v-model="google.allowedService"
					class="switch"
					:state="google.allowedService"
				/>
			</AppInputSwitch>

			<div v-if="config.isGoogleLoginConfigured && google.allowedService" @click="google.isVisibleCredentialsForm = !google.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': google.isVisibleCredentialsForm}">
				<edit2-icon size="12" class="vue-feather text-theme mr-2" />
				<b class="text-xs">{{ $t('Update Your Credentials') }}</b>
			</div>

			<!--Set up Google credentials-->
			<ValidationObserver
				v-if="(! config.isGoogleLoginConfigured || google.isVisibleCredentialsForm) && google.allowedService"
				@submit.prevent="storeCredentials('google')"
				ref="credentialsForm"
				v-slot="{ invalid }"
				tag="form"
				class="p-5 shadow-lg rounded-xl"
			>
				<FormLabel v-if="! config.isGoogleLoginConfigured" icon="shield">
					{{ $t('Configure Credentials') }}
				</FormLabel>

				<ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client ID')" :error="errors[0]">
						<input v-model="google.credentials.client_id" :placeholder="$t('Paste your Client ID here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client Secret')" :error="errors[0]">
						<input v-model="google.credentials.client_secret" :placeholder="$t('Paste your Client Secret here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
					{{ $t('Store Credentials') }}
				</ButtonBase>
			</ValidationObserver>

		</div>

		<!--Github Social Authentication-->
		<div class="card shadow-card">
			<img :src="$getSocialLogo('github')" alt="Github" class="mb-8 h-5">

			<AppInputSwitch :title="$t('Allow Login via GitHub')" :description="$t('You users will be able to login via GitHub account.')" :is-last="! github.allowedService">
				<SwitchInput
					@input="$updateText('/admin/settings', 'allowed_github_login', github.allowedService)"
					v-model="github.allowedService"
					class="switch"
					:state="github.allowedService"
				/>
			</AppInputSwitch>

			<div v-if="config.isGithubLoginConfigured && github.allowedService" @click="github.isVisibleCredentialsForm = !github.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': github.isVisibleCredentialsForm}">
				<edit2-icon size="12" class="vue-feather text-theme mr-2" />
				<b class="text-xs">{{ $t('Update Your Credentials') }}</b>
			</div>

			<!--Set up github credentials-->
			<ValidationObserver
				v-if="(! config.isGithubLoginConfigured || github.isVisibleCredentialsForm) && github.allowedService"
				@submit.prevent="storeCredentials('github')"
				ref="credentialsForm"
				v-slot="{ invalid }"
				tag="form"
				class="p-5 shadow-lg rounded-xl"
			>
				<FormLabel v-if="! config.isGithubLoginConfigured" icon="shield">
					{{ $t('Configure Credentials') }}
				</FormLabel>

				<ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client ID')" :error="errors[0]">
						<input v-model="github.credentials.client_id" :placeholder="$t('Paste your Client ID here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Client Secret')" :error="errors[0]">
						<input v-model="github.credentials.client_secret" :placeholder="$t('Paste your Client Secret here')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
					{{ $t('Store Credentials') }}
				</ButtonBase>
			</ValidationObserver>

		</div>
    </PageTab>
</template>

<script>
    import {
		Edit2Icon,
	} from 'vue-feather-icons'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch"
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
	import AppInputText from "../../../../components/Admin/AppInputText"
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {events} from '/resources/js/bus'
	import {mapGetters} from "vuex"
	import axios from 'axios'
	import AppInputButton from "../../../../components/Admin/AppInputButton";

	export default {
		name: 'AppOthers',
		components: {
			AppInputButton,
			ValidationObserver,
			ValidationProvider,
			AppInputSwitch,
			AppInputText,
			PageTabGroup,
			SwitchInput,
			SelectInput,
			ImageInput,
			ButtonBase,
			Edit2Icon,
			FormLabel,
			SetupBox,
			required,
			PageTab,
			InfoBox,
		},
		computed: {
			...mapGetters([
				'config',
			])
		},
		data() {
			return {
				isLoading: true,
				isFlushingCache: false,
				app: undefined,
				facebook: {
					allowedService: false,
					isVisibleCredentialsForm: false,
					credentials: {
						key: undefined,
						secret: undefined,
					},
				},
				google: {
					allowedService: false,
					isVisibleCredentialsForm: false,
					credentials: {
						key: undefined,
						secret: undefined,
					},
				},
				github: {
					allowedService: false,
					isVisibleCredentialsForm: false,
					credentials: {
						key: undefined,
						secret: undefined,
					},
				},
			}
		},
		methods: {
			async storeCredentials(service) {

				// Validate fields
				const isValid = await this.$refs.credentialsForm.validate();

				if (!isValid) return;

				// Start loading
				this.isLoading = true

				// Send request to get verify account
				axios
					.post('/api/admin/settings/social-service', {
						client_id: this[service].credentials.client_id,
						client_secret: this[service].credentials.client_secret,
						service: service,
					})
					.then(() => {
						// Commit credentials
						this.$store.commit('SET_SOCIAL_LOGIN_CONFIGURED', service)

						this[service].allowedService = true
						this[service].isVisibleCredentialsForm = false

						// Show toaster
						events.$emit('toaster', {
							type: 'success',
							message: this.$t('toaster.credentials_set', {service: service}),
						})
					})
					.catch(error => {

						if (error.response.status === 500) {
							this.isError = true
							this.errorMessage = error.response.data.message
						}
					})
					.finally(() => this.isLoading = false)
			},
			flushCache() {

				this.isFlushingCache = true

				axios.get('/api/admin/settings/flush-cache')
					.then(() => {
						events.$emit('toaster', {
							type: 'success',
							message: 'Your cache was successfully deleted.',
						})
					})
					.finally(() => {
						this.isFlushingCache = false
					})
			}
		},
		mounted() {
			this.facebook.allowedService = this.config.allowedFacebookLogin
			this.google.allowedService = this.config.allowedGoogleLogin
			this.github.allowedService = this.config.allowedGithubLogin

			axios.get('/api/admin/settings', {
					params: {
						column: 'contact_email|google_analytics|default_max_storage_amount|registration|storage_limitation|mimetypes_blacklist|upload_limit|user_verification'
					}
				})
				.then(response => {
					this.isLoading = false

					this.app = {
						contactMail: response.data.contact_email,
						googleAnalytics: response.data.google_analytics,
						defaultStorage: response.data.default_max_storage_amount,
						userRegistration: parseInt(response.data.registration),
						storageLimitation: parseInt(response.data.storage_limitation),
						mimetypesBlacklist: response.data.mimetypes_blacklist,
						uploadLimit: response.data.upload_limit,
						userVerification: parseInt(response.data.user_verification)
					}
				})
		}
	}
</script>
