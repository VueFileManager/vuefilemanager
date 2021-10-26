<template>
    <PageTab>
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
            <FormLabel icon="smartphone">{{ $t('2fa.settings.title') }}</FormLabel>
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
		<PageTabGroup class="form block-form">
            <FormLabel icon="key">{{ $t('personal_token.section_title') }}</FormLabel>
			<InfoBox v-if="tokens.length === 0">
				<p>{{ $t("personal_token.section_description") }}</p>
			</InfoBox>

			<InfoBox v-if="tokens.length > 0">
				<ul class="tokens-wrapper">
					<li class="token-item" v-for="token in tokens" :key="token.id">
						<div class="tokens-details">
							<b class="name">{{ token.name}}</b>
							<time class="last-used">{{ $t('last_used') }}: {{ token.last_used_at ? formatDate(token.last_used_at) : $t('never') }}</time>
						</div>
						<div @click="confirmDeleteToken(token)" class="tokens-destroyer">
							<x-icon size="16" class="close-icon hover-text-theme" />
						</div>
					</li>
				</ul>
			</InfoBox>

			<ButtonBase @click.native="openCreateTokenPopup" type="submit" button-style="theme" class="confirm-form">
				{{ $t('personal_token.create_token') }}
			</ButtonBase>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import UserImageInput from '/resources/js/components/Others/UserImageInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import PageHeader from '/resources/js/components/Others/PageHeader'
	import ThemeLabel from '/resources/js/components/Others/ThemeLabel'
	import {required} from 'vee-validate/dist/rules'
	import { XIcon } from 'vue-feather-icons'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import axios from 'axios'

	export default {
		name: 'Password',
		components: {
			PageTabGroup,
			FormLabel,
			PageTab,
			InfoBox,
			XIcon,
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
			formatDate(date) {
				return new Intl.DateTimeFormat('en').format(new Date(date))
			},
			confirmDeleteToken(token) {
				events.$emit('confirm:open', {
					title: this.$t('popup_delete_personal_token.title'),
					message: this.$t('popup_delete_personal_token.description'),
					action: {
						id: token.id,
						operation: 'delete-personal-access-token'
					}
				})
			},
			deleteToken(id) {
				axios.delete(`/api/user/tokens/${id}`)
					.then(() => {

						this.tokens = this.tokens.filter(tokenItem => tokenItem.id !== id)

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('personal_token.token_deleted'),
						})
					})
					.catch(() => this.$isSomethingWrong())
			},
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

			// Delete personal access token
			events.$on('action:confirmed', data => {

				if (data.operation === 'delete-personal-access-token') {
					this.deleteToken(data.id)
				}
			})

			events.$on('reload-personal-access-tokens', () => this.getPersonalAccessTokens())
		}
	}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';
	@import '/resources/sass/vuefilemanager/_forms';

	.tokens-wrapper {
		margin-top: 0 !important;

		.token-item {
			display: flex;
			justify-content: space-between;
			width: 100%;
			padding: 10px 0;
			border-bottom: 1px solid darken($light_mode_border, 5%);
			align-items: center;

			&:first-child {
				padding-top: 0;
			}

			&:last-child {
				border-bottom: 0 solid transparent;
				padding-bottom: 0;
			}
		}

		.tokens-details {
			.name {
				@include font-size(16);
			}

			.last-used {
				@include font-size(12);
				color: $text-muted;
				line-height: 1.35;
				display: block;
			}
		}

		.tokens-destroyer {
			margin-top: 10px;

			.close-icon {
				opacity: 0.2;

				&:hover {
					opacity: 1;

					line {
						color: inherit;
					}
				}
			}
		}

		.tokens-destroyer {
			cursor: pointer;
		}
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

	.dark {
		.tokens-wrapper {
			margin-top: 0 !important;

			.token-item {
				border-color: lighten($dark_mode_foreground, 3%);
			}

			.tokens-details {

				.last-used {
					color: $dark_mode_text_secondary;
				}
			}
		}
	}

</style>
