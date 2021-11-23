<template>
    <PopupWrapper name="two-factor-authentication-confirm">

        <PopupHeader :title="$t('popup_2fa.title')" icon="edit" />

        <PopupContent>
             <ValidationObserver @submit.prevent="confirmPassword" v-if="! qrCode" ref="passwordForm" v-slot="{ invalid }" tag="form" class="form-wrapper">
                <ValidationProvider tag="div" mode="passive" name="Password" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('popup_2fa.input_label')" :error="errors[0]" :is-last="true">
						<input v-model="password" :class="{'is-error': errors[0]}" type="password" ref="input" class="focus-border-theme input-dark" :placeholder="$t('page_sign_in.placeholder_password')">
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>

            <div v-if="qrCode" class="qr-code-wrapper">
                <div class="qr-code">
                    <div v-html="qrCode"></div>
                </div>

				<InfoBox style="margin-bottom: 0">
                	<p v-html="$t('popup_2fa.help')"></p>
            	</InfoBox>
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
import AppInputText from "../Admin/AppInputText";
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
import {required} from 'vee-validate/dist/rules'
import {mapGetters} from 'vuex'
import {events} from '/resources/js/bus'
import axios from 'axios'

export default {
	name: "TwoFactorSetupPopup",
	components: {
		ValidationProvider,
		ValidationObserver,
		AppInputText,
		PopupWrapper,
		PopupActions,
		PopupContent,
		PopupHeader,
		ButtonBase,
		required,
		InfoBox,
	},
	computed: {
		...mapGetters(['user']),
		closeQrButtonText() {
			return this.isConfirmedClose
				? this.$t('popup_2fa.disappear_qr')
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

				this.$closePopup()
			}
		}
	}
}
</script>

<style lang="scss" scoped>
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

.qr-code-wrapper {
	padding: 0 20px;

	.qr-code {
		display: flex;
		justify-content: center;
		margin: 20px 0;
	}
}
</style>
