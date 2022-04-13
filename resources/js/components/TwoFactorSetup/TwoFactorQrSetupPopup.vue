<template>
    <PopupWrapper name="two-factor-qr-setup">
        <PopupHeader :title="$t('set_up_2fa_app')" icon="edit" />

        <PopupContent>
            <div v-if="qrCode" class="flex justify-center">
                <div v-html="qrCode" class="my-5"></div>
            </div>

            <InfoBox style="margin-bottom: 0">
                <p v-html="$t('popup_2fa.help')"></p>
            </InfoBox>

            <ValidationObserver @submit.prevent="confirm2FaSetup" ref="codeForm" v-slot="{ invalid }" tag="form" class="mt-5">
                <ValidationProvider tag="div" mode="passive" name="Code" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('confirm')" :error="errors[0]" :is-last="true">
                        <input
							v-model="code"
							:class="{ '!border-rose-600': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('paste_code_from_2fa_app')"
						/>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>

        </PopupContent>

        <PopupActions>
            <ButtonBase @click.native="confirm2FaSetup" class="w-full" button-style="theme" :loading="isLoading">
                {{ $t('confirm_your_code') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import { required } from 'vee-validate/dist/rules'
import ButtonBase from '../UI/Buttons/ButtonBase'
import AppInputText from '../Forms/Layouts/AppInputText'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupActions from '../Popups/Components/PopupActions'
import PopupContent from '../Popups/Components/PopupContent'
import PopupHeader from '../Popups/Components/PopupHeader'
import InfoBox from '../UI/Others/InfoBox'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'TwoFactorQrSetupPopup',
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
    data() {
        return {
            qrCode: undefined,
            isLoading: false,
			code: undefined
        }
    },
    methods: {
		async confirm2FaSetup() {
			// Validate fields
			const isValid = await this.$refs.codeForm.validate()

			if (!isValid) return

			this.isLoading = true

			axios
				.post('/user/confirmed-two-factor-authentication', {code: this.code})
				.then(() => {
					this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', true)

					this.$closePopup()

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('popup_2fa.toaster_enabled'),
					})
				})
				.catch((error) => {
					if (error.response.status === 422) {
						this.$refs.codeForm.setErrors({
							'Code': error.response.data.errors['code'][0],
						})
					}
				})
				.finally(() => this.isLoading = false)
		},
        enable() {
            axios
                .post('/user/two-factor-authentication')
				.then(() => {
					this.getQrCode()
				})
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
        getQrCode() {
            axios
                .get('/user/two-factor-qr-code')
                .then((response) => {
                    this.qrCode = response.data.svg
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
    },
    created() {
        events.$on('popup:open', (args) => {
            if (args.name !== 'two-factor-qr-setup') return

			this.enable()
        })
    },
}
</script>
