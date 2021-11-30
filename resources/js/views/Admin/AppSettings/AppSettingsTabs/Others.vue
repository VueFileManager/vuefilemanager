<template>
    <PageTab :is-loading="isLoading">
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('admin_settings.others.section_user') }}
			</FormLabel>

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

			<AppInputSwitch :title="$t('admin_settings.others.allow_registration')" :description="$t('admin_settings.others.allow_registration_help')">
				<SwitchInput
					@input="$updateText('/admin/settings', 'registration', app.userRegistration)"
					v-model="app.userRegistration"
					class="switch"
					:state="app.userRegistration"
				/>
			</AppInputSwitch>

			<AppInputSwitch :title="$t('admin_settings.others.allow_user_verification')" :description="$t('admin_settings.others.allow_user_verification_help')" :is-last="true">
				<SwitchInput
					@input="$updateText('/admin/settings', 'user_verification', app.userVerification)"
					v-model="app.userVerification"
					class="switch"
					:state="app.userVerification"
				/>
			</AppInputSwitch>
		</div>
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('admin_settings.others.section_others') }}
			</FormLabel>

			<AppInputText :title="$t('admin_settings.others.contact_email')">
				<input class="focus-border-theme input-dark" @input="$updateText('/admin/settings', 'contact_email', app.contactMail)" v-model="app.contactMail" :placeholder="$t('admin_settings.others.contact_email_plac')" type="email" />
			</AppInputText>

			<AppInputText :title="$t('admin_settings.others.google_analytics')">
				<input @input="$updateText('/admin/settings', 'google_analytics', app.googleAnalytics, true)" v-model="app.googleAnalytics" :placeholder="$t('admin_settings.others.google_analytics_plac')" type="text" class="focus-border-theme input-dark" />
			</AppInputText>

			<AppInputText :title="$t('admin_settings.others.mimetypes_blacklist')" :description="$t('admin_settings.others.mimetypes_blacklist_help')">
				<textarea rows="2" @input="$updateText('/admin/settings', 'mimetypes_blacklist', app.mimetypesBlacklist, true)" v-model="app.mimetypesBlacklist" :placeholder="$t('admin_settings.others.mimetypes_blacklist_plac')" type="text" class="focus-border-theme input-dark" />
			</AppInputText>

			<AppInputText :title="$t('admin_settings.others.upload_limit')" :description="$t('admin_settings.others.upload_limit_help')" :is-last="true">
				<input @input="$updateText('/admin/settings', 'upload_limit', app.uploadLimit, true)" v-model="app.uploadLimit" :placeholder="$t('admin_settings.others.upload_limit_plac')" type="number" min="0" step="1" class="focus-border-theme input-dark" />
			</AppInputText>
		</div>
		<div class="card shadow-card">
			<FormLabel>
				{{ $t('admin_settings.others.section_cache') }}
			</FormLabel>
			<InfoBox>
				{{ $t('admin_settings.others.cache_disclaimer') }}
			</InfoBox>
			<ButtonBase @click.native="flushCache" :loading="isFlushingCache" :disabled="isFlushingCache" type="submit" button-style="theme" class="submit-button">
				{{ $t('admin_settings.others.cache_clear') }}
			</ButtonBase>
		</div>
    </PageTab>
</template>

<script>
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch";
	import AppInputText from "../../../../components/Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {events} from '/resources/js/bus'
	import axios from 'axios'

	export default {
		name: 'AppOthers',
		components: {
			AppInputSwitch,
			AppInputText,
			ValidationObserver,
			ValidationProvider,
			PageTabGroup,
			SwitchInput,
			SelectInput,
			ImageInput,
			ButtonBase,
			FormLabel,
			SetupBox,
			required,
			PageTab,
			InfoBox,
		},
		data() {
			return {
				isLoading: true,
				isFlushingCache: false,
				app: undefined,
			}
		},
		methods: {
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
