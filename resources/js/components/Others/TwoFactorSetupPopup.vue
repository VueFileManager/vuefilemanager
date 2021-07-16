<template>
    <PopupWrapper name="two-factor-authentication-confirm">

        <PopupHeader :title="$t('popup_2fa.title')" icon="edit" />

        <PopupContent>
             <ValidationObserver @submit.prevent="confirmPassword" v-if="! qrCode" ref="passwordForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <label class="input-label"> {{ $t('popup_2fa.input_label') }}:</label>
                    <input v-model="password" :class="{'is-error': errors[0]}" type="password" ref="input" class="focus-border-theme" :placeholder="$t('page_sign_in.placeholder_password')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <div v-if="qrCode" class="qr-code-wrapper">
                <div class="qr-code">
                    <div v-html="qrCode"></div>
                </div>

                <small class="input-help" v-html="$t('popup_2fa.help')"></small>
            </div>
        </PopupContent>

        <PopupActions v-if="! qrCode">
            <ButtonBase
				class="popup-button"
				@click.native="$closePopup()"
				button-style="secondary"
			>
                {{ $t('global.cancel') }}
            </ButtonBase>
            <ButtonBase
				class="popup-button"
				@click.native="confirmPassword"
				button-style="theme"
				:loading="isLoading"
				:disabled="isLoading"
			>
               {{ $t('popup_2fa.confirm_button') }}
            </ButtonBase>
        </PopupActions>

        <PopupActions v-if="qrCode">
            <ButtonBase
				class="popup-button"
				@click.native="closeQrCodePopup"
				:button-style="closeQrButtonStyle"
			>
                {{ closeQrButtonText }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
import PopupActions from '@/components/Others/Popup/PopupActions'
import PopupContent from '@/components/Others/Popup/PopupContent'
import PopupHeader from '@/components/Others/Popup/PopupHeader'
import ButtonBase from '@/components/FilesView/ButtonBase'
import {required} from 'vee-validate/dist/rules'
import {mapGetters} from 'vuex'
import {events} from '@/bus'
import axios from 'axios'

export default {
	name: "TwoFactorSetupPopup",
	components: {
		ValidationProvider,
		ValidationObserver,
		PopupWrapper,
		PopupActions,
		PopupContent,
		PopupHeader,
		ButtonBase,
		required,
	},
	computed: {
		...mapGetters(['user']),
		closeQrButtonText() {
			return this.isConfirmedClose
				? 'Really disappear this QR code?'
				: this.$t('shared_form.button_done')
		},
		closeQrButtonStyle() {
			return this.isConfirmedClose
				? 'danger'
				: 'theme'
		},
	},
	data() {
		return {
			isLoading: false,
			password: '',
			qrCode: '',
			isConfirmedClose: false,
		}
	},
	methods: {
		confirmPassword() {

			this.isLoading = true

			axios
				.post('/user/confirm-password', {
					password: this.password
				})
				.then(() => {

					if (!this.user.data.attributes.two_factor_authentication) {

						this.enable()
					} else {

						this.disable()
					}
				})
				.catch(error => {

					if (error.response.status == 422) {

						this.$refs.passwordForm.setErrors({
							'Password': this.$t('validation_errors.incorrect_password')
						});
					}
				})
				.finally(() => {
					this.isLoading = false
					this.password = undefined
				})
		},
		enable() {
			axios
				.post('/user/two-factor-authentication')
				.then(() => {

					this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', true)

					this.getQrCode()
				})
				.catch(() => {
					this.$isSomethingWrong()
				})
		},
		disable() {
			axios
				.delete('/user/two-factor-authentication')
				.then(() => {
					this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', false)
				})
				.catch(() => {
					this.$isSomethingWrong()
				})
				.finally(() => {
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('popup_2fa.toaster_disabled'),
					})

					this.$closePopup()
				})
		},
		getQrCode() {
			axios
				.get('/user/two-factor-qr-code')
				.then(response => {
					this.qrCode = response.data.svg
				})
				.catch(() => {
					this.$isSomethingWrong()
				})
		},
		closeQrCodePopup() {
			if (!this.isConfirmedClose) {
				this.isConfirmedClose = true
			} else {
				events.$emit('toaster', {
					type: 'success',
					message: this.$t('popup_2fa.toaster_enabled'),
				})

				this.qrCode = undefined
				this.isConfirmedClose = false

				axios.get('/user/two-factor-recovery-codes')
					.then(response => {
						console.log(response.data);
					})

				this.$closePopup()
			}
		}
	}
}
</script>

<style lang="scss" scoped>
@import "@assets/vuefilemanager/_inapp-forms.scss";
@import '@assets/vuefilemanager/_forms';

.qr-code-wrapper {
	padding: 0 20px;

	.qr-code {
		display: flex;
		justify-content: center;
		margin: 20px 0;
	}
}
</style>
