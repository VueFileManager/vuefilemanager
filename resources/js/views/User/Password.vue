<template>
    <div v-if="user">

		<!--2fa authentication-->
        <div v-if="! user.data.attributes.socialite_account" class="card shadow-card">
            <FormLabel icon="smartphone">
                {{ $t('2fa.settings.title') }}
            </FormLabel>
            <AppInputSwitch :title="$t('popup_2fa.switch_title')" :description="$t('popup_2fa.switch_info')" :is-last="! user.data.attributes.two_factor_authentication">
                <SwitchInput v-model="user.data.attributes.two_factor_authentication" class="switch" :state="user.data.attributes.two_factor_authentication" />
            </AppInputSwitch>
            <AppInputSwitch v-if="user && user.data.attributes.two_factor_authentication" :title="$t('popup_2fa.codes_title')" :description="$t('popup_2fa.codes_info')" :is-last="true">
                <ButtonBase class="w-full" button-style="secondary" @click.native="showRecoveryCodes">
                    {{ $t('popup_2fa.codes_button') }}
                </ButtonBase>
            </AppInputSwitch>
        </div>

		<!--Get personal api keys-->
        <div class="card shadow-card">
            <FormLabel icon="key">
                {{ $t('personal_token.section_title') }}
            </FormLabel>
            <InfoBox v-if="tokens.length === 0">
                <p>{{ $t("personal_token.section_description") }}</p>
            </InfoBox>
            <InfoBox v-if="tokens.length > 0">
                <ul class="tokens-wrapper">
                    <li class="token-item" v-for="token in tokens" :key="token.id">
                        <div class="tokens-details">
                            <b class="name">{{ token.name }}</b>
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
        </div>

		<!--Change password-->
		<ValidationObserver ref="password" @submit.prevent="resetPassword" v-slot="{ invalid }" tag="form" class="card shadow-card">
			<FormLabel>
				{{ $t('user_password.title') }}
			</FormLabel>

			<ValidationProvider tag="div" mode="passive" name="Current Password" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('Current Password')" :error="errors[0]">
					<input v-model="passwordForm.current" :placeholder="$t('Current password')" type="password" class="focus-border-theme input-dark" :class="{'border-red': errors[0]}" />
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="New Password" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('page_create_password.label_new_pass')" :error="errors[0]">
					<input v-model="passwordForm.password" :placeholder="$t('page_create_password.label_new_pass')" type="password" class="focus-border-theme input-dark" :class="{'border-red': errors[0]}" />
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Confirm Your Password" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('page_create_password.label_confirm_pass')" :error="errors[0]">
					<input v-model="passwordForm.password_confirmation" :placeholder="$t('page_create_password.label_confirm_pass')" type="password" class="focus-border-theme input-dark" :class="{'border-red': errors[0]}" />
				</AppInputText>
			</ValidationProvider>

			<ButtonBase type="submit" button-style="theme" class="confirm-form">
				{{ $t('profile.store_pass') }}
			</ButtonBase>
		</ValidationObserver>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import UserImageInput from '/resources/js/components/Others/UserImageInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import PageHeader from '/resources/js/components/Others/PageHeader'
	import ThemeLabel from '/resources/js/components/Others/ThemeLabel'
	import AppInputSwitch from "../../components/Admin/AppInputSwitch"
	import AppInputText from "../../components/Admin/AppInputText"
	import {required} from 'vee-validate/dist/rules'
	import {XIcon} from 'vue-feather-icons'
	import {events} from '/resources/js/bus'
	import {mapGetters} from 'vuex'
	import axios from 'axios'

	export default {
		name: 'Password',
		components: {
			ValidationProvider,
			ValidationObserver,
			UserImageInput,
			AppInputSwitch,
			AppInputText,
			SwitchInput,
			PageHeader,
			ButtonBase,
			ThemeLabel,
			FormLabel,
			required,
			InfoBox,
			XIcon,
		},
		computed: {
			...mapGetters([
				'user',
			])
		},
		watch: {
			'user.data.attributes.two_factor_authentication': function (val) {
				val ? this.enable2faPopup() : this.disable2faPopup()
			}
		},
		data() {
			return {
				passwordForm: {
					current: undefined,
					password: undefined,
					password_confirmation: undefined,
				},
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
					.post(this.$store.getters.api + '/user/password', this.passwordForm)
					.then(() => {

						// Reset inputs
						this.passwordForm = {
							current: undefined,
							password: undefined,
							password_confirmation: undefined,
						}

						// Reset errors
						this.$refs.password.reset()

						// Show success message
						events.$emit('success:open', {
							title: this.$t('popup_pass_changed.title'),
							message: this.$t('popup_pass_changed.message'),
						})
					})
					.catch(error => {

						if (error.response.status === 422) {

							if (error.response.data.errors['password']) {
								this.$refs.password.setErrors({
									'New Password': error.response.data.errors['password']
								});
							}

							if (error.response.data.errors['current']) {
								this.$refs.password.setErrors({
									'Current Password': error.response.data.errors['current']
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
			showRecoveryCodes() {
				events.$emit('popup:open', {
					name: 'confirm-password',
					options: {
						action: 'get-recovery-codes',
					}
				})
			},
			enable2faPopup() {
				events.$emit('popup:open', {
					name: 'confirm-password',
					options: {
						action: 'two-factor-qr-setup',
					}
				})
			},
			disable2faPopup() {
				events.$emit('popup:open', {
					name: 'confirm-password',
					options: {
						action: 'disable-2fa',
					}
				})
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
			openCreateTokenPopup() {
				events.$emit('popup:open', {name: 'create-personal-token'})
			},
			formatDate(date) {
				return new Intl.DateTimeFormat('en').format(new Date(date))
			},
		},
		created() {
			this.getPersonalAccessTokens()

			// Actions confirmed
			events.$on('action:confirmed', data => {

				// Delete personal token
				if (data.operation === 'delete-personal-access-token') {
					axios.delete(`/api/user/tokens/${data.id}`)
						.then(() => {

							this.tokens = this.tokens.filter(tokenItem => tokenItem.id !== data.id)

							events.$emit('toaster', {
								type: 'success',
								message: this.$t('personal_token.token_deleted'),
							})
						})
						.catch(() => this.$isSomethingWrong())
				}
			})

			// Password confirmed
			events.$on('password:confirmed', args => {

				// Get recovery tokens
				if (args.options.action === 'get-recovery-codes') {
					events.$emit('popup:open', {name: 'two-factor-recovery-codes'})
				}

				// Get 2fa qr code
				if (args.options.action === 'two-factor-qr-setup') {
					events.$emit('popup:open', {name: 'two-factor-qr-setup'})
				}

				// Get 2fa qr code
				if (args.options.action === 'disable-2fa') {
					axios
						.delete('/user/two-factor-authentication')
						.then(() => {
							this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', false)
						})
						.catch(() => {
							this.$isSomethingWrong()
						})
						.finally(() => {
							this.$closePopup()

							events.$emit('toaster', {
								type: 'success',
								message: this.$t('popup_2fa.toaster_disabled'),
							})
						})
				}
			})

			events.$on('reload-personal-access-tokens', () => this.getPersonalAccessTokens())
		},
		destroyed() {
			events.$off('action:confirmed')
		},
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
