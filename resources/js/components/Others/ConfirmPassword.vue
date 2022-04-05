<template>
    <PopupWrapper name="confirm-password">
        <PopupHeader :title="$t('confirm_password')" icon="edit" />

        <PopupContent>
            <ValidationObserver @submit.prevent="confirmPassword" ref="passwordForm" v-slot="{ invalid }" tag="form">
                <ValidationProvider tag="div" mode="passive" name="Password" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('password')" :error="errors[0]" :is-last="true">
                        <input
                            v-model="password"
                            :class="{ '!border-rose-600': errors[0] }"
                            type="password"
                            ref="input"
                            class="focus-border-theme input-dark"
                            :placeholder="$t('page_sign_in.placeholder_password')"
                        />
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
                @click.native="confirmPassword"
                button-style="theme"
                :loading="isLoading"
                :disabled="isLoading"
            >
                {{ $t('confirm') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from './Popup/PopupWrapper'
import PopupActions from './Popup/PopupActions'
import PopupContent from './Popup/PopupContent'
import PopupHeader from './Popup/PopupHeader'
import ButtonBase from '../FilesView/ButtonBase'
import AppInputText from '../Admin/AppInputText'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'ConfirmPassword',
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
    },
    computed: {
        ...mapGetters(['user']),
    },
    data() {
        return {
            isLoading: false,
            password: undefined,
            args: undefined,
        }
    },
    methods: {
        confirmPassword() {
            this.isLoading = true

            axios
                .post('/user/confirm-password', {
                    password: this.password,
                })
                .then(() => {
                    events.$emit('password:confirmed', this.args)
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.$refs.passwordForm.setErrors({
                            Password: this.$t('validation_errors.incorrect_password'),
                        })
                    }
                })
                .finally(() => {
                    this.isLoading = false
                    this.password = undefined
                })
        },
    },
    created() {
        // Show popup
        events.$on('popup:open', (args) => {
            if (args.name !== 'confirm-password') return

            this.args = args
        })
    },
}
</script>
