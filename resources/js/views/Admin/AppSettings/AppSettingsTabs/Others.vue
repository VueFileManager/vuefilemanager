<template>
    <PageTab :is-loading="isLoading">

		<!--Store & Upload-->
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('storage_upload') }}
            </FormLabel>

			<!--Available only when is not metered billing-->
            <div v-if="config.subscriptionType !== 'metered'">
                <AppInputSwitch
					:title="$t('admin_settings.others.storage_limit')"
					:description="$t('admin_settings.others.storage_limit_help')"
				>
                    <SwitchInput
						@input="$updateText('/admin/settings', 'storage_limitation', app.storageLimitation)"
						v-model="app.storageLimitation"
						:state="app.storageLimitation"
						class="switch"
					/>
                </AppInputSwitch>

                <AppInputText v-if="app.storageLimitation" :title="$t('admin_settings.others.default_storage')">
                    <input
						@input="$updateText('/admin/settings', 'default_max_storage_amount', app.defaultStorage)"
						v-model="app.defaultStorage"
						min="1"
						max="999999999"
						:placeholder="$t('admin_settings.others.default_storage_plac')"
						type="number"
						class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </div>

            <AppInputText
				:title="$t('admin_settings.others.upload_limit')"
				:description="$t('admin_settings.others.upload_limit_help')"
			>
                <input
					@input="$updateText('/admin/settings', 'upload_limit', app.uploadLimit, true)"
					v-model="app.uploadLimit"
					:placeholder="$t('admin_settings.others.upload_limit_plac')"
					type="number"
					min="0"
					step="1"
					class="focus-border-theme input-dark"
				/>
            </AppInputText>

            <AppInputText
				:title="$t('File Chunk Size (in MB)')"
				:description="$t('When you upload file on the server, your file will be sliced into many chunks in this size. Small size of the chunk can prevent many limits you can suffer from your server or provider. Default value is 64MB if is not set.')"
			>
                <input
					@input="$updateText('/admin/settings', 'chunk_size', app.chunkSize, true)"
					v-model="app.chunkSize"
					:placeholder="$t('Type the chunk size in MB')"
					type="number"
					min="0"
					step="1"
					class="focus-border-theme input-dark"
				/>
            </AppInputText>

            <AppInputText
				:title="$t('admin_settings.others.mimetypes_blacklist')"
				:description="$t('admin_settings.others.mimetypes_blacklist_help')"
				:is-last="true"
			>
                <textarea
					rows="2"
					@input="$updateText('/admin/settings', 'mimetypes_blacklist', app.mimetypesBlacklist, true)"
					v-model="app.mimetypesBlacklist"
					:placeholder="$t('admin_settings.others.mimetypes_blacklist_plac')"
					type="text"
					class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>

		<!-- ReCaptcha -->
        <div v-if="app" class="card shadow-card">
            <FormLabel icon="shield">
                {{ $t('reCaptcha') }}
            </FormLabel>

            <AppInputSwitch
				:title="$t('allow_recaptcha')"
				:description="$t('allow_recaptcha_note')"
				:is-last="!recaptcha.allowedService"
			>
                <SwitchInput
					@input="$updateText('/admin/settings', 'allowed_recaptcha', recaptcha.allowedService)"
					v-model="recaptcha.allowedService"
					class="switch"
					:state="recaptcha.allowedService"
				/>
            </AppInputSwitch>

            <div
				v-if="config.isRecaptchaConfigured && recaptcha.allowedService"
				@click="recaptcha.isVisibleCredentialsForm = !recaptcha.isVisibleCredentialsForm"
				class="flex cursor-pointer items-center"
				:class="{ 'mb-4': recaptcha.isVisibleCredentialsForm }"
			>
                <edit2-icon size="12" class="vue-feather text-theme mr-2" />
                <b class="text-xs">{{ $t('update_your_credentials') }}</b>
            </div>

			<!--Set up recaptcha credentials-->
            <ValidationObserver
				v-if="(!config.isRecaptchaConfigured || recaptcha.isVisibleCredentialsForm) && recaptcha.allowedService"
				@submit.prevent="storeCredentials('recaptcha')"
				ref="credentialsForm"
				v-slot="{ invalid }"
				tag="form"
				class="rounded-xl p-5 shadow-lg"
			>
                <FormLabel v-if="!config.isRecaptchaConfigured" icon="shield">
                    {{ $t('configure_your_credentials') }}
                </FormLabel>

                <ValidationProvider tag="div" mode="passive" name="Site Key" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Site Key')" :error="errors[0]">
                        <input
							v-model="recaptcha.credentials.client_id"
							:placeholder="$t('Paste your Site Key here')"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
							class="focus-border-theme input-dark"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Secret key" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Secret Key')" :error="errors[0]">
                        <input
							v-model="recaptcha.credentials.client_secret"
							:placeholder="$t('Paste your Secret key here')"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
							class="focus-border-theme input-dark"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ButtonBase
					:disabled="isLoading"
					:loading="isLoading"
					button-style="theme"
					type="submit"
					class="w-full"
				>
                    {{ $t('store_credentials') }}
                </ButtonBase>
            </ValidationObserver>
        </div>

		<!--Other Settings-->
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('application') }}
            </FormLabel>

            <AppInputButton
				:title="$t('cache')"
				:description="$t('cache_note')"
			>
                <ButtonBase
					@click.native="flushCache"
					:loading="isFlushingCache"
					:disabled="isFlushingCache"
					class="w-full sm:w-auto"
					button-style="theme"
				>
                    {{ $t('clear_cache') }}
                </ButtonBase>
            </AppInputButton>

            <AppInputText :title="$t('admin_settings.others.contact_email')">
                <input
					class="focus-border-theme input-dark"
					@input="$updateText('/admin/settings', 'contact_email', app.contactMail)"
					v-model="app.contactMail"
					:placeholder="$t('admin_settings.others.contact_email_plac')"
					type="email"
				/>
            </AppInputText>

            <AppInputText :title="$t('admin_settings.others.google_analytics')" :is-last="true">
                <input
					@input="$updateText('/admin/settings', 'google_analytics', app.googleAnalytics, true)"
					v-model="app.googleAnalytics"
					:placeholder="$t('admin_settings.others.google_analytics_plac')"
					type="text"
					class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>

		<!--Upgrade License-->
		<div v-if="app && !config.isSaaS" class="card shadow-card">
			<FormLabel icon="trending-up">
				{{ $t('Upgrade your License') }}
			</FormLabel>

			<ValidationObserver
				ref="upgradeLicense"
				@submit.prevent="upgradeLicense"
				v-slot="{ invalid }"
				tag="form"
				class="mt-6"
			>
				<ValidationProvider
					tag="div"
					v-slot="{ errors }"
					mode="passive"
					name="Purchase Code"
					rules="required"
				>
					<AppInputText
						:error="errors[0]"
						:is-last="true"
					>
						<div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
							<input
								v-model="purchaseCode"
								:placeholder="$t('Paste your Purchase code here...')"
								type="text"
								class="focus-border-theme input-dark"
								:class="{ '!border-rose-600': errors[0] }"
							/>
							<ButtonBase type="submit" button-style="theme" :loading="isLoadingUpgradingButton" class="w-full sm:w-auto">
								{{ $t('Upgrade') }}
							</ButtonBase>
						</div>
					</AppInputText>
				</ValidationProvider>
			</ValidationObserver>
    	</div>

		<!-- Subscription -->
        <div v-if="app && config.isSaaS" class="card shadow-card">
            <FormLabel icon="credit-card">
                {{ $t('subscription') }}
            </FormLabel>

            <AppInputText :title="$t('subscription_type')" :description="$t('subscription_type_note')" :is-last="true">
                <SelectInput
					@change="subscriptionTypeChange"
					:default="app.subscriptionType"
					:options="subscriptionTypes"
					:placeholder="$t('select_subscription_type')"
				/>
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import {Edit2Icon} from 'vue-feather-icons'
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import SwitchInput from '../../../../components/Others/Forms/SwitchInput'
import AppInputButton from '../../../../components/Admin/AppInputButton'
import AppInputSwitch from '../../../../components/Admin/AppInputSwitch'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import ButtonBase from '../../../../components/FilesView/ButtonBase'
import AppInputText from '../../../../components/Admin/AppInputText'
import PageTab from '../../../../components/Others/Layout/PageTab'
import {required} from 'vee-validate/dist/rules'
import {events} from '../../../../bus'
import {mapGetters} from 'vuex'
import axios from 'axios'
import SelectInput from "../../../../components/Others/Forms/SelectInput";

export default {
	name: 'AppOthers',
	components: {
		SelectInput,
		AppInputButton,
		ValidationObserver,
		ValidationProvider,
		AppInputSwitch,
		AppInputText,
		SwitchInput,
		ButtonBase,
		Edit2Icon,
		FormLabel,
		required,
		PageTab,
	},
	computed: {
		...mapGetters(['subscriptionTypes', 'config']),
	},
	data() {
		return {
			isLoading: true,
			isLoadingUpgradingButton: false,
			isFlushingCache: false,
			app: undefined,
			purchaseCode: undefined,
			recaptcha: {
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
		async upgradeLicense() {
			this.isLoadingUpgradingButton = true
			// Validate fields
			const isValid = await this.$refs.upgradeLicense.validate()

			if (!isValid) return

			axios.post('/api/admin/upgrade-license', {
				purchaseCode: this.purchaseCode
			})
			.then((response) => {
				this.$store.dispatch('getLanguageTranslations', this.config.locale)

				this.$store.commit('REPLACE_CONFIG_VALUE', {
					key: 'isSaaS',
					value: true,
				})

				events.$emit('toaster', {
					type: 'success',
					message: this.$t('Your license was successfully upgraded'),
				})
			})
			.catch((error) => {
				if (error.response.status === 400) {
					events.$emit('alert:open', {
						title: this.$t('Purchase code is invalid or is not Extended License'),
					})
				} else {
					events.$emit('alert:open', {
						title: this.$t('popup_error.title'),
						message: this.$t('popup_error.message'),
					})
				}
			})
			.finally(() => {
				this.isLoadingUpgradingButton = false
			})
		},
		subscriptionTypeChange(type) {
			events.$emit('confirm:open', {
				title: this.$t(''),
				message: this.$t(
					'subscription_type_change_warn_description'
				),
				action: {
					type: type,
					operation: 'change-subscription-type',
				},
			})
		},
		async storeCredentials(service) {
			// Validate fields
			const isValid = await this.$refs.credentialsForm.validate()

			if (!isValid) return

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
						message: this.$t('toaster.credentials_set', {
							service: service,
						}),
					})
				})
				.catch((error) => {
					if (error.response.status === 500) {
						this.isError = true
						this.errorMessage = error.response.data.message
					}
				})
				.finally(() => (this.isLoading = false))
		},
		flushCache() {
			this.isFlushingCache = true

			axios
				.get('/api/admin/settings/flush-cache')
				.then(() => {
					events.$emit('toaster', {
						type: 'success',
						message: 'Your cache was successfully deleted.',
					})
				})
				.finally(() => {
					this.isFlushingCache = false
				})
		},
	},
	mounted() {
		this.recaptcha.allowedService = this.config.allowedRecaptcha

		axios
			.get('/api/admin/settings', {
				params: {
					column: 'contact_email|google_analytics|default_max_storage_amount|storage_limitation|mimetypes_blacklist|upload_limit|subscriptionType|chunk_size',
				},
			})
			.then((response) => {
				this.isLoading = false

				this.app = {
					contactMail: response.data.contact_email,
					googleAnalytics: response.data.google_analytics,
					defaultStorage: response.data.default_max_storage_amount,
					storageLimitation: parseInt(response.data.storage_limitation),
					mimetypesBlacklist: response.data.mimetypes_blacklist,
					uploadLimit: response.data.upload_limit,
					subscriptionType: response.data.subscriptionType,
					chunkSize: response.data.chunk_size,
				}
			})
	},
	created() {
		events.$on('action:confirmed', (data) => {
			if (data.operation === 'change-subscription-type') {

				// Update database
				this.$updateText('/admin/settings', 'subscription_type', data.type)

				// Update config
				this.$store.commit('REPLACE_CONFIG_VALUE', {
					key: 'subscriptionType',
					value: data.type,
				})
			}
		})
	},
	destroyed() {
		events.$off('action:confirmed')
	},
}
</script>
