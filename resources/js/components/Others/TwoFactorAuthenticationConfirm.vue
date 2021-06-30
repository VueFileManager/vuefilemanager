<template>
    <PopupWrapper name="two-factor-authentication-confirm">

        <PopupHeader :title="$t('popup_two_factor_authentication.title')" icon="edit"/>

        <PopupContent>
             <ValidationObserver @submit.prevent="confirmPassword" v-if="! qrCode" ref="passwordForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <label class="input-label"> {{ $t('popup_two_factor_authentication.input_label') }}:</label>
                    <input v-model="password" :class="{'is-error': errors[0]}" type="password" ref="input" class="focus-border-theme" :placeholder="$t('popup_two_factor_authentication.input_label')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <div v-if="qrCode" class="qr-code-wrapper">
                <div class="qr-code">
                    <div v-html="qrCode"></div>
                </div>

                <small class="input-help" v-html="$t('popup_two_factor_authentication.help')" ></small>
            </div>
        </PopupContent>

        <PopupActions>
            <ButtonBase
                class="popup-button"
                @click.native="$closePopup()"
                button-style="secondary"
            >
                {{ $t('global.cancel') }}
            </ButtonBase>
            <ButtonBase
                v-if="! qrCode"
                class="popup-button"
                @click.native="confirmPassword"
                button-style="theme"
                :loading="isLoading"
                :disabled="isLoading"
            >
               {{ $t('popup_two_factor_authentication.confirm_button') }}
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
    name: "TwoFactorAuthenticationConfirm",
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
        ...mapGetters(['user'])
    },
    data () {
        return {
            isLoading: false,
            password: '',
            qrCode: '',
        }
    },
    methods: {
        confirmPassword () {

            this.isLoading = true

            axios.
                post('/user/confirm-password', {
                    password: this.password
                })
                .then(() => {

                   if(! this.user.data.attributes.two_factor_authentication) {

                       this.enable()
                   } else {

                       this.disable()
                   }

                    this.isLoading = false
                })
                .catch(error => {

                    if (error.response.status == 422) {

                        this.$refs.passwordForm.setErrors({
                            'Password': this.$t('validation_errors.incorrect_password')
                        });
                    }
                })
        },
        enable() {

            axios.
                post('/user/two-factor-authentication')
                .then(() => {

                    this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', true)

                    this.getQrCode()
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
        disable() {

            axios.
                delete('/user/two-factor-authentication')
                .then(() => {

                    this.$store.commit('CHANGE_TWO_FACTOR_AUTHENTICATION_STATE', false)

                    this.$closePopup()
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
        getQrCode() {
            
            axios.
                get('/user/two-factor-qr-code')
                .then((response) => {
                    this.qrCode = response.data.svg
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
    },
    mounted () {

        events.$on('popup:open', ({name}) => {

            if (name === 'two-factor-authentication-confirm')
                this.password = ''
                this.qrCode = ''
        })
        }
}
</script>

<style lang="scss" scoped>
@import "@assets/vuefilemanager/_inapp-forms.scss";
@import '@assets/vuefilemanager/_forms';

.qr-code-wrapper {
    padding: 0px 20px;

    .qr-code {
        display: flex;
        justify-content: center;
        margin: 20px 0px 20px 0px;
    }
}

</style>
