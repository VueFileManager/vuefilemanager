<template>
    <PopupWrapper name="two-factor-qr-setup">
        <PopupHeader :title="$t('confirm_your_password')" icon="edit" />

        <PopupContent>
            <div v-if="qrCode" class="flex justify-center">
                <div v-html="qrCode" class="my-5"></div>
            </div>

            <InfoBox style="margin-bottom: 0">
                <p v-html="$t('popup_2fa.help')"></p>
            </InfoBox>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="closeQrCodePopup" :button-style="closeQrButtonStyle">
                {{ closeQrButtonText }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from '../Admin/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from './Popup/PopupWrapper'
import PopupActions from './Popup/PopupActions'
import PopupContent from './Popup/PopupContent'
import PopupHeader from './Popup/PopupHeader'
import ButtonBase from '../FilesView/ButtonBase'
import InfoBox from './Forms/InfoBox'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
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
    computed: {
        ...mapGetters(['user']),
        closeQrButtonText() {
            return this.isConfirmedClose ? this.$t('popup_2fa.disappear_qr') : this.$t('awesome_iam_done')
        },
        closeQrButtonStyle() {
            return this.isConfirmedClose ? 'danger' : 'theme'
        },
    },
    data() {
        return {
            isLoading: false,
            qrCode: '',
            isConfirmedClose: false,
        }
    },
    methods: {
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
        },
    },
    created() {
        // Show popup
        events.$on('popup:open', (args) => {
            if (args.name !== 'two-factor-qr-setup') return

            this.enable()
        })
    },
}
</script>
