<template>
    <PageTab>
		<PageTabGroup class="form block-form">
            <FormLabel>{{ $t('Personal Access Token') }}</FormLabel>
			<InfoBox v-if="tokens.length === 0">
				<p>{{ $t("You don't have any created personal access tokens yet.") }}</p>
			</InfoBox>

			<ButtonBase @click.native="openCreateTokenPopup" type="submit" button-style="theme" class="confirm-form">
				{{ $t('Create Token') }}
			</ButtonBase>
        </PageTabGroup>
        <PageTabGroup>
            <ValidationObserver ref="password" @submit.prevent="resetPassword" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>{{ $t('user_password.title') }}</FormLabel>
                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_new_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="New Password"
										rules="required" v-slot="{ errors }">
                        <input v-model="newPassword" :placeholder="$t('page_create_password.label_new_pass')"
							   type="password"
							   class="focus-border-theme"
							   :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_confirm_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
										rules="required" v-slot="{ errors }">
                        <input v-model="newPasswordConfirmation"
							   :placeholder="$t('page_create_password.label_confirm_pass')" type="password"
							   class="focus-border-theme"
							   :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="block-wrapper">
                    <ButtonBase type="submit" button-style="theme" class="confirm-form">
                        {{ $t('profile.store_pass') }}
                    </ButtonBase>
                </div>
            </ValidationObserver>
        </PageTabGroup>
        <PageTabGroup class="form block-form">
            <FormLabel>{{ $t('2fa.settings.title') }}</FormLabel>
            <div class="block-wrapper">
				<div class="input-wrapper">
					<div class="inline-wrapper">
						<div class="switch-label">
							<label class="input-label">
								{{ $t('popup_2fa.switch_title') }}
							</label>
							<small class="input-help" v-html="$t('popup_2fa.switch_info')"></small>
						</div>
						<SwitchInput @click.native.prevent.stop="open2faPopup"
									 class="switch"
									 :state="user.data.attributes.two_factor_authentication"
						/>
					</div>
				</div>
			</div>
            <div v-if="user && user.data.attributes.two_factor_authentication" class="block-wrapper">
				<div class="input-wrapper">
					<div class="inline-wrapper button-block">
						<div class="switch-label">
							<label class="input-label">
								{{ $t('popup_2fa.codes_title') }}
							</label>
							<small class="input-help">
								{{ $t('popup_2fa.codes_info') }}
							</small>
						</div>
						<ButtonBase
							class="popup-button"
							button-style="secondary"
							@click.native="showRecoveryCodes"
						>
							{{ $t('popup_2fa.codes_button') }}
						</ButtonBase>
					</div>
				</div>
			</div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
	import UserImageInput from '@/components/Others/UserImageInput'
	import SwitchInput from '@/components/Others/Forms/SwitchInput'
	import FormLabel from '@/components/Others/Forms/FormLabel'
	import MobileHeader from '@/components/Mobile/MobileHeader'
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import PageTab from '@/components/Others/Layout/PageTab'
	import InfoBox from '@/components/Others/Forms/InfoBox'
	import PageHeader from '@/components/Others/PageHeader'
	import ThemeLabel from '@/components/Others/ThemeLabel'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {events} from '@/bus'
	import axios from 'axios'

	export default {
		name: 'Password',
		components: {
			PageTabGroup,
			FormLabel,
			PageTab,
			InfoBox,
			ValidationProvider,
			ValidationObserver,
			UserImageInput,
			SwitchInput,
			MobileHeader,
			PageHeader,
			ButtonBase,
			ThemeLabel,
			required,
		},
		computed: {
			...mapGetters(['user'])
		},
		data() {
			return {
				newPasswordConfirmation: '',
				newPassword: '',
				isLoading: false,
				tokens: [],
			}
		},
		methods: {
			async resetPassword() {

				// Validate fields
				const isValid = await this.$refs.password.validate();

				if (!isValid) return;

				// Send request to get user reset link
				axios
					.post(this.$store.getters.api + '/user/password', {
						password: this.newPassword,
						password_confirmation: this.newPasswordConfirmation,
					})
					.then(() => {

						// Reset inputs
						this.newPassword = ''
						this.newPasswordConfirmation = ''

						// Reset errors
						this.$refs.password.reset()

						// Show error message
						events.$emit('success:open', {
							title: this.$t('popup_pass_changed.title'),
							message: this.$t('popup_pass_changed.message'),
						})
					})
					.catch(error => {

						if (error.response.status == 422) {

							if (error.response.data.errors['password']) {

								this.$refs.password.setErrors({
									'New Password': error.response.data.errors['password']
								});
							}
						}
					})
			},
			getPersonalAccessTokens() {
				axios.get('/api/user/tokens')
					.then(response => {
						this.tokens = response.data
					})
					.catch(() => this.$isSomethingWrong())
			},
			open2faPopup() {
				events.$emit('popup:open', {name: 'two-factor-authentication-confirm'})
			},
			showRecoveryCodes() {
				events.$emit('popup:open', {name: 'two-factor-recovery-codes'})
			},
			openCreateTokenPopup() {
				events.$emit('popup:open', {name: 'create-personal-token'})
			}
		},
		created() {
			this.getPersonalAccessTokens()
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

	@media only screen and (max-width: 960px) {

		.form {
			.button-base {
				width: 100%;
				margin-top: 0;
				text-align: center;
			}
		}
	}

	@media only screen and (max-width: 690px) {

		.form .button-block {
			display: block;

			.popup-button {
				margin-top: 15px;
			}
		}
	}

	@media (prefers-color-scheme: dark) {

	}

</style>
