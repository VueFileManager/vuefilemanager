<template>
    <PageTab :is-loading="isLoading">

		<!-- Subscription -->
        <div v-if="app" class="card shadow-card">
            <FormLabel icon="credit-card">
                {{ $t('subscription') }}
            </FormLabel>

            <AppInputText :description="$t('subscription_type_note')" :is-last="true" :title="$t('subscription_type')">
                <SelectInput
					:default="app.subscriptionType"
					:options="subscriptionTypes"
					:placeholder="$t('select_subscription_type')"
					@change="subscriptionTypeChange"
				/>
            </AppInputText>
        </div>

		<div v-if="app" class="card shadow-card">
			<FormLabel>
                {{ $t('upload_settings') }}
            </FormLabel>

			<AppInputText
				:description="$t('admin_settings.others.upload_limit_help')"
				:title="$t('admin_settings.others.upload_limit')"
			>
                <input
					v-model="app.uploadLimit"
					:placeholder="$t('admin_settings.others.upload_limit_plac')"
					class="focus-border-theme input-dark"
					min="0"
					step="1"
					type="number"
					@input="$updateText('/admin/settings', 'upload_limit', app.uploadLimit, true)"
				/>
            </AppInputText>

            <AppInputText
				:description="$t('When you upload file on the server, your file will be sliced into many chunks in this size. Small size of the chunk can prevent many limits you can suffer from your server or provider. Default value is 64MB if is not set.')"
				:title="$t('File Chunk Size (in MB)')"
			>
                <input
					v-model="app.chunkSize"
					:placeholder="$t('Type the chunk size in MB')"
					class="focus-border-theme input-dark"
					min="0"
					step="1"
					type="number"
					@input="$updateText('/admin/settings', 'chunk_size', app.chunkSize, true)"
				/>
            </AppInputText>

            <AppInputText
				:description="$t('admin_settings.others.mimetypes_blacklist_help')"
				:is-last="true"
				:title="$t('admin_settings.others.mimetypes_blacklist')"
			>
                <textarea
					v-model="app.mimetypesBlacklist"
					:placeholder="$t('admin_settings.others.mimetypes_blacklist_plac')"
					class="focus-border-theme input-dark"
					rows="2"
					type="text"
					@input="$updateText('/admin/settings', 'mimetypes_blacklist', app.mimetypesBlacklist, true)"
				/>
            </AppInputText>
		</div>

		<!--Store & Upload-->
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('user_features') }}
            </FormLabel>

			<!--Available only when is not metered billing-->
            <div v-if="config.subscriptionType !== 'metered'">
                <AppInputSwitch
					:description="$t('admin_settings.others.storage_limit_help')"
					:title="$t('admin_settings.others.storage_limit')"
				>
                    <SwitchInput
						v-model="app.storageLimitation"
						:state="app.storageLimitation"
						class="switch"
						@input="$updateText('/admin/settings', 'storage_limitation', app.storageLimitation)"
					/>
                </AppInputSwitch>

                <AppInputText v-if="app.storageLimitation" :title="$t('admin_settings.others.default_storage')">
                    <input
						v-model="app.defaultStorage"
						:placeholder="$t('admin_settings.others.default_storage_plac')"
						class="focus-border-theme input-dark"
						max="999999999"
						min="1"
						type="number"
						@input="$updateText('/admin/settings', 'default_max_storage_amount', app.defaultStorage)"
					/>
                </AppInputText>
            </div>

			<AppInputText :description="$t('zero_for_unlimited_members')" :is-last="true" :title="$t('max_team_members')">
				<input
					v-model="app.teamsDefaultMembers"
					:placeholder="$t('admin_settings.others.default_storage_plac')"
					class="focus-border-theme input-dark"
					max="999999999"
					min="1"
					type="number"
					@input="$updateText('/admin/settings', 'default_max_team_member', app.teamsDefaultMembers)"
				/>
			</AppInputText>
        </div>

		<!-- ReCaptcha -->
        <div v-if="app" class="card shadow-card">
            <FormLabel icon="shield">
                {{ $t('reCaptcha') }}
            </FormLabel>

            <AppInputSwitch
				:description="$t('allow_recaptcha_note')"
				:is-last="!recaptcha.allowedService"
				:title="$t('allow_recaptcha')"
			>
                <SwitchInput
					v-model="recaptcha.allowedService"
					:state="recaptcha.allowedService"
					class="switch"
					@input="$updateText('/admin/settings', 'allowed_recaptcha', recaptcha.allowedService)"
				/>
            </AppInputSwitch>

            <div
				v-if="config.isRecaptchaConfigured && recaptcha.allowedService"
				:class="{ 'mb-4': recaptcha.isVisibleCredentialsForm }"
				class="flex cursor-pointer items-center"
				@click="recaptcha.isVisibleCredentialsForm = !recaptcha.isVisibleCredentialsForm"
			>
                <edit2-icon class="vue-feather text-theme mr-2" size="12" />
                <b class="text-xs">{{ $t('update_your_credentials') }}</b>
            </div>

			<!--Set up recaptcha credentials-->
            <ValidationObserver
				v-if="(!config.isRecaptchaConfigured || recaptcha.isVisibleCredentialsForm) && recaptcha.allowedService"
				ref="credentialsForm"
				v-slot="{ invalid }"
				class="rounded-xl p-5 shadow-lg"
				tag="form"
				@submit.prevent="storeCredentials('recaptcha')"
			>
                <FormLabel v-if="!config.isRecaptchaConfigured" icon="shield">
                    {{ $t('configure_your_credentials') }}
                </FormLabel>

                <ValidationProvider v-slot="{ errors }" mode="passive" name="Site Key" rules="required" tag="div">
                    <AppInputText :error="errors[0]" :title="$t('Site Key')">
                        <input
							v-model="recaptcha.credentials.client_id"
							:class="{ '!border-rose-600': errors[0] }"
							:placeholder="$t('Paste your Site Key here')"
							class="focus-border-theme input-dark"
							type="text"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider v-slot="{ errors }" mode="passive" name="Secret key" rules="required" tag="div">
                    <AppInputText :error="errors[0]" :title="$t('Secret Key')">
                        <input
							v-model="recaptcha.credentials.client_secret"
							:class="{ '!border-rose-600': errors[0] }"
							:placeholder="$t('Paste your Secret key here')"
							class="focus-border-theme input-dark"
							type="text"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ButtonBase
					:disabled="isLoading"
					:loading="isLoading"
					button-style="theme"
					class="w-full"
					type="submit"
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
				:description="$t('cache_note')"
				:title="$t('cache')"
			>
                <ButtonBase
					:disabled="isFlushingCache"
					:loading="isFlushingCache"
					button-style="theme"
					class="w-full sm:w-auto"
					@click.native="flushCache"
				>
                    {{ $t('clear_cache') }}
                </ButtonBase>
            </AppInputButton>

            <AppInputText :title="$t('admin_settings.others.contact_email')">
                <input
					v-model="app.contactMail"
					:placeholder="$t('admin_settings.others.contact_email_plac')"
					class="focus-border-theme input-dark"
					type="email"
					@input="$updateText('/admin/settings', 'contact_email', app.contactMail)"
				/>
            </AppInputText>

            <AppInputText :is-last="true" :title="$t('admin_settings.others.google_analytics')">
                <input
					v-model="app.googleAnalytics"
					:placeholder="$t('admin_settings.others.google_analytics_plac')"
					class="focus-border-theme input-dark"
					type="text"
					@input="$updateText('/admin/settings', 'google_analytics', app.googleAnalytics, true)"
				/>
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import {Edit2Icon} from 'vue-feather-icons'
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import SwitchInput from '../../../../components/Inputs/SwitchInput'
import AppInputButton from '../../../../components/Forms/Layouts/AppInputButton'
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import PageTab from '../../../../components/Layout/PageTab'
import {required} from 'vee-validate/dist/rules'
import {events} from '../../../../bus'
import {mapGetters} from 'vuex'
import axios from 'axios'
import SelectInput from "../../../../components/Inputs/SelectInput";

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
		subscriptionTypeChange(type) {
			events.$emit('confirm:open', {
				title: this.$t('subscription_type_change_warn'),
				message: this.$t('subscription_type_change_warn_description'),
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
					column: 'contact_email|google_analytics|default_max_storage_amount|storage_limitation|mimetypes_blacklist|upload_limit|subscriptionType|chunk_size|default_max_team_member',
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
					teamsDefaultMembers: response.data.default_max_team_member,
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
