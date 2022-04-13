<template>
    <div v-if="user">
        <!--2fa authentication-->
        <div v-if="!user.data.attributes.socialite_account" class="card shadow-card">
            <FormLabel icon="smartphone">
                {{ $t('2fa.settings.title') }}
            </FormLabel>
            <AppInputSwitch
                :title="$t('popup_2fa.switch_title')"
                :description="$t('popup_2fa.switch_info')"
                :is-last="!user.data.attributes.two_factor_confirmed_at"
            >
                <SwitchInput
					@click.native="toggle2Fa"
					:is-disabled="true"
                    v-model="user.data.attributes.two_factor_confirmed_at"
                    class="switch"
                    :state="user.data.attributes.two_factor_confirmed_at"
                />
            </AppInputSwitch>
            <AppInputButton
                v-if="user && user.data.attributes.two_factor_confirmed_at"
                :title="$t('show_recovery_codes')"
                :description="$t('popup_2fa.codes_info')"
                :is-last="true"
            >
                <ButtonBase class="w-full" button-style="secondary" @click.native="showRecoveryCodes">
                    {{ $t('show_codes') }}
                </ButtonBase>
            </AppInputButton>
        </div>

        <!--Get personal api keys-->
        <div class="card shadow-card">
            <FormLabel icon="key">
                {{ $t('personal_access_token') }}
            </FormLabel>
            <InfoBox v-if="tokens.length === 0">
                <p>{{ $t('personal_token.section_description') }}</p>
            </InfoBox>

            <div class="mb-5">
                <div
                    v-if="tokens.length > 0"
                    class="flex items-center justify-between border-b border-dashed border-light py-2 dark:border-opacity-5"
                    v-for="token in tokens"
                    :key="token.id"
                >
                    <div class="leading-none">
                        <b class="text-sm font-bold leading-none">
                            {{ token.name }}
                        </b>
                        <time class="block pt-2 text-xs leading-none dark:text-gray-500 text-gray-500">
                            {{ $t('last_used') }}:
                            {{ token.last_used_at ? formatDate(token.last_used_at) : $t('never') }}
                        </time>
                    </div>
                    <div class="text-right">
                        <div
                            @click="confirmDeleteToken(token)"
                            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-md bg-light-background transition-colors hover:bg-red-100 dark:bg-2x-dark-foreground"
                        >
                            <Trash2Icon size="15" class="opacity-75" />
                        </div>
                    </div>
                </div>
            </div>

            <ButtonBase
                @click.native="openCreateTokenPopup"
                type="submit"
                button-style="theme"
                class="w-full sm:w-auto"
            >
                {{ $t('create_token') }}
            </ButtonBase>
        </div>

        <!--Change password-->
        <ValidationObserver
            ref="password"
            @submit.prevent="resetPassword"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <FormLabel>
                {{ $t('user_password.title') }}
            </FormLabel>

            <ValidationProvider tag="div" mode="passive" name="Current Password" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('current_password')" :error="errors[0]">
                    <input
                        v-model="passwordForm.current"
                        :placeholder="$t('current_password')"
                        type="password"
                        class="focus-border-theme input-dark"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                </AppInputText>
            </ValidationProvider>

            <ValidationProvider tag="div" mode="passive" name="New Password" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('new_password')" :error="errors[0]">
                    <input
                        v-model="passwordForm.password"
                        :placeholder="$t('new_password')"
                        type="password"
                        class="focus-border-theme input-dark"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                </AppInputText>
            </ValidationProvider>

            <ValidationProvider
                tag="div"
                mode="passive"
                name="Confirm Your Password"
                rules="required"
                v-slot="{ errors }"
            >
                <AppInputText :title="$t('confirm_password')" :error="errors[0]">
                    <input
                        v-model="passwordForm.password_confirmation"
                        :placeholder="$t('confirm_password')"
                        type="password"
                        class="focus-border-theme input-dark"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                </AppInputText>
            </ValidationProvider>

            <ButtonBase type="submit" button-style="theme" class="w-full sm:w-auto">
                {{ $t('profile.store_pass') }}
            </ButtonBase>
        </ValidationObserver>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SwitchInput from '../../components/Inputs/SwitchInput'
import FormLabel from '../../components/UI/Labels/FormLabel'
import ButtonBase from '../../components/UI/Buttons/ButtonBase'
import InfoBox from '../../components/UI/Others/InfoBox'
import AppInputSwitch from '../../components/Forms/Layouts/AppInputSwitch'
import AppInputButton from '../../components/Forms/Layouts/AppInputButton'
import AppInputText from '../../components/Forms/Layouts/AppInputText'
import { required } from 'vee-validate/dist/rules'
import { XIcon, Trash2Icon } from 'vue-feather-icons'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'Password',
    components: {
        ValidationProvider,
        ValidationObserver,
        AppInputButton,
        AppInputSwitch,
        AppInputText,
        SwitchInput,
        ButtonBase,
        FormLabel,
        Trash2Icon,
        required,
        InfoBox,
        XIcon,
    },
    computed: {
        ...mapGetters(['user']),
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
		toggle2Fa() {
			this.user.data.attributes.two_factor_confirmed_at ? this.disable2faPopup() : this.enable2faPopup()
		},
        async resetPassword() {
            // Validate fields
            const isValid = await this.$refs.password.validate()

            if (!isValid) return

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
                .catch((error) => {
                    if (error.response.status === 422) {
                        if (error.response.data.errors['password']) {
                            this.$refs.password.setErrors({
                                'New Password': error.response.data.errors['password'],
                            })
                        }

                        if (error.response.data.errors['current']) {
                            this.$refs.password.setErrors({
                                'Current Password': error.response.data.errors['current'],
                            })
                        }
                    }
                })
        },
        getPersonalAccessTokens() {
            axios
                .get('/api/user/tokens')
                .then((response) => {
                    this.tokens = response.data
                })
                .catch(() => this.$isSomethingWrong())
        },
        showRecoveryCodes() {
            events.$emit('popup:open', {
                name: 'confirm-password',
                options: {
                    action: 'get-recovery-codes',
                },
            })
        },
        enable2faPopup() {
            events.$emit('popup:open', {
                name: 'confirm-password',
                options: {
                    action: 'two-factor-qr-setup',
                },
            })
        },
        disable2faPopup() {
            events.$emit('popup:open', {
                name: 'confirm-password',
                options: {
                    action: 'disable-2fa',
                },
            })
        },
        confirmDeleteToken(token) {
            events.$emit('confirm:open', {
                title: this.$t('popup_delete_personal_token.title'),
                message: this.$t('popup_delete_personal_token.description'),
                action: {
                    id: token.id,
                    operation: 'delete-personal-access-token',
                },
            })
        },
        openCreateTokenPopup() {
            events.$emit('popup:open', { name: 'create-personal-token' })
        },
        formatDate(date) {
            return new Intl.DateTimeFormat('en').format(new Date(date))
        },
    },
    created() {
        this.getPersonalAccessTokens()

        // Actions confirmed
        events.$on('action:confirmed', (data) => {
            // Delete personal token
            if (data.operation === 'delete-personal-access-token') {
                axios
                    .delete(`/api/user/tokens/${data.id}`)
                    .then(() => {
                        this.tokens = this.tokens.filter((tokenItem) => tokenItem.id !== data.id)

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('personal_token.token_deleted'),
                        })
                    })
                    .catch(() => this.$isSomethingWrong())
            }
        })

        // Password confirmed
        events.$on('password:confirmed', (args) => {
            // Get recovery tokens
            if (args.options.action === 'get-recovery-codes') {
                events.$emit('popup:open', {
                    name: 'two-factor-recovery-codes',
                })
            }

            // Get 2fa qr code
            if (args.options.action === 'two-factor-qr-setup') {
                events.$emit('popup:open', { name: 'two-factor-qr-setup' })
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
