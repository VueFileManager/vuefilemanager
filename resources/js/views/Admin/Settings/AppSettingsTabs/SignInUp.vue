<template>
    <PageTab>
        <!--User Login/Registration-->
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('User Login/Registration') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('admin_settings.others.allow_registration')"
                :description="$t('admin_settings.others.allow_registration_help')"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'registration', app.userRegistration)"
                    v-model="app.userRegistration"
                    class="switch"
                    :state="app.userRegistration"
                />
            </AppInputSwitch>

            <AppInputSwitch
                :title="$t('require_email_verification')"
				:description="$t('require_email_verification_note')"
                :is-last="true"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'user_verification', app.userVerification)"
                    v-model="app.userVerification"
                    class="switch"
                    :state="app.userVerification"
                />
            </AppInputSwitch>
        </div>

        <!--Facebook Social Authentication-->
        <div class="card shadow-card">
            <img :src="$getSocialLogo('facebook')" alt="Facebook" class="mb-8 h-5" />

            <AppInputSwitch
                :title="$t('Allow Login via Facebook')"
                :description="$t('You users will be able to login via Facebook account.')"
                :is-last="!facebook.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_facebook_login', facebook.allowedService)"
                    v-model="facebook.allowedService"
                    class="switch"
                    :state="facebook.allowedService"
                />
            </AppInputSwitch>

            <AppInputText
				v-if="facebook.allowedService"
                :title="$t('Your Callback URL')"
                :description="$t('Please copy your url and paste it to the service callback URL.')"
            >
                <CopyInput size="small" :str="getCallbackEndpoint('facebook')" />
            </AppInputText>

            <div
                v-if="config.isFacebookLoginConfigured && facebook.allowedService"
                @click="facebook.isVisibleCredentialsForm = !facebook.isVisibleCredentialsForm"
                class="flex cursor-pointer items-center"
                :class="{ 'mb-4': facebook.isVisibleCredentialsForm }"
            >
                <edit2-icon size="12" class="vue-feather text-theme mr-2" />
                <b class="text-xs">{{ $t('update_your_credentials') }}</b>
            </div>

            <!--Set up facebook credentials-->
            <ValidationObserver
                v-if="
                    (!config.isFacebookLoginConfigured || facebook.isVisibleCredentialsForm) && facebook.allowedService
                "
                @submit.prevent="storeCredentials('facebook')"
                ref="facebook"
                v-slot="{ invalid }"
                tag="form"
                class="rounded-xl p-5 shadow-lg"
            >
                <FormLabel v-if="!config.isFacebookLoginConfigured" icon="shield">
                    {{ $t('Configure Credentials') }}
                </FormLabel>

                <ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client ID')" :error="errors[0]">
                        <input
                            v-model="facebook.credentials.client_id"
                            :placeholder="$t('Paste your Client ID here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client Secret')" :error="errors[0]">
                        <input
                            v-model="facebook.credentials.client_secret"
                            :placeholder="$t('Paste your Client Secret here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ButtonBase
                    :disabled="isLoading"
                    :loading="isLoading"
                    button-style="theme"
                    type="submit"
                    class="w-full"
                >
                    {{ $t('store_credentials') }}
                </ButtonBase>
            </ValidationObserver>
        </div>

        <!--Google Social Authentication-->
        <div class="card shadow-card">
            <img :src="$getSocialLogo('google')" alt="Google" class="mb-8 h-7" />

            <AppInputSwitch
                :title="$t('Allow Login via Google')"
                :description="$t('You users will be able to login via Google account.')"
                :is-last="!google.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_google_login', google.allowedService)"
                    v-model="google.allowedService"
                    class="switch"
                    :state="google.allowedService"
                />
            </AppInputSwitch>

			<AppInputText
				v-if="google.allowedService"
				:title="$t('Your Callback URL')"
				:description="$t('Please copy your url and paste it to the service callback URL.')"
			>
                <CopyInput size="small" :str="getCallbackEndpoint('google')" />
            </AppInputText>

            <div
                v-if="config.isGoogleLoginConfigured && google.allowedService"
                @click="google.isVisibleCredentialsForm = !google.isVisibleCredentialsForm"
                class="flex cursor-pointer items-center"
                :class="{ 'mb-4': google.isVisibleCredentialsForm }"
            >
                <edit2-icon size="12" class="vue-feather text-theme mr-2" />
                <b class="text-xs">{{ $t('update_your_credentials') }}</b>
            </div>

            <!--Set up Google credentials-->
            <ValidationObserver
                v-if="(!config.isGoogleLoginConfigured || google.isVisibleCredentialsForm) && google.allowedService"
                @submit.prevent="storeCredentials('google')"
                ref="google"
                v-slot="{ invalid }"
                tag="form"
                class="rounded-xl p-5 shadow-lg"
            >
                <FormLabel v-if="!config.isGoogleLoginConfigured" icon="shield">
                    {{ $t('Configure Credentials') }}
                </FormLabel>

                <ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client ID')" :error="errors[0]">
                        <input
                            v-model="google.credentials.client_id"
                            :placeholder="$t('Paste your Client ID here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client Secret')" :error="errors[0]">
                        <input
                            v-model="google.credentials.client_secret"
                            :placeholder="$t('Paste your Client Secret here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ButtonBase
                    :disabled="isLoading"
                    :loading="isLoading"
                    button-style="theme"
                    type="submit"
                    class="w-full"
                >
                    {{ $t('store_credentials') }}
                </ButtonBase>
            </ValidationObserver>
        </div>

        <!--Github Social Authentication-->
        <div class="card shadow-card">
            <img :src="$getSocialLogo('github')" alt="Github" class="mb-8 h-5" />

            <AppInputSwitch
                :title="$t('Allow Login via GitHub')"
                :description="$t('You users will be able to login via GitHub account.')"
                :is-last="!github.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_github_login', github.allowedService)"
                    v-model="github.allowedService"
                    class="switch"
                    :state="github.allowedService"
                />
            </AppInputSwitch>

			<AppInputText
				v-if="github.allowedService"
				:title="$t('Your Callback URL')"
				:description="$t('Please copy your url and paste it to the service callback URL.')"
			>
                <CopyInput size="small" :str="getCallbackEndpoint('github')" />
            </AppInputText>

            <div
                v-if="config.isGithubLoginConfigured && github.allowedService"
                @click="github.isVisibleCredentialsForm = !github.isVisibleCredentialsForm"
                class="flex cursor-pointer items-center"
                :class="{ 'mb-4': github.isVisibleCredentialsForm }"
            >
                <edit2-icon size="12" class="vue-feather text-theme mr-2" />
                <b class="text-xs">{{ $t('update_your_credentials') }}</b>
            </div>

            <!--Set up github credentials-->
            <ValidationObserver
                v-if="(!config.isGithubLoginConfigured || github.isVisibleCredentialsForm) && github.allowedService"
                @submit.prevent="storeCredentials('github')"
                ref="github"
                v-slot="{ invalid }"
                tag="form"
                class="rounded-xl p-5 shadow-lg"
            >
                <FormLabel v-if="!config.isGithubLoginConfigured" icon="shield">
                    {{ $t('Configure Credentials') }}
                </FormLabel>

                <ValidationProvider tag="div" mode="passive" name="Client ID" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client ID')" :error="errors[0]">
                        <input
                            v-model="github.credentials.client_id"
                            :placeholder="$t('Paste your Client ID here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Client Secret" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Client Secret')" :error="errors[0]">
                        <input
                            v-model="github.credentials.client_secret"
                            :placeholder="$t('Paste your Client Secret here')"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ButtonBase
                    :disabled="isLoading"
                    :loading="isLoading"
                    button-style="theme"
                    type="submit"
                    class="w-full"
                >
                    {{ $t('store_credentials') }}
                </ButtonBase>
            </ValidationObserver>
        </div>
    </PageTab>
</template>

<script>
import { Edit2Icon } from 'vue-feather-icons'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SwitchInput from '../../../../components/Inputs/SwitchInput'
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import CopyInput from '../../../../components/Inputs/CopyInput'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import PageTab from '../../../../components/Layout/PageTab'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'SignInUp',
    components: {
        CopyInput,
        ValidationObserver,
        ValidationProvider,
        AppInputSwitch,
        AppInputText,
        SwitchInput,
        ButtonBase,
        Edit2Icon,
        FormLabel,
        required,
        PageTab,
        events,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            isLoading: false,
            app: {
                userRegistration: undefined,
                userVerification: undefined,
            },
            facebook: {
                allowedService: false,
                isVisibleCredentialsForm: false,
                credentials: {
                    key: undefined,
                    secret: undefined,
                },
            },
            google: {
                allowedService: false,
                isVisibleCredentialsForm: false,
                credentials: {
                    key: undefined,
                    secret: undefined,
                },
            },
            github: {
                allowedService: false,
                isVisibleCredentialsForm: false,
                credentials: {
                    key: undefined,
                    secret: undefined,
                },
            },
        }
    },
    methods: {
        getCallbackEndpoint(service) {
			if (service === 'facebook') {
            	return `${this.config.host}/socialite/${service}/callback/`
			}

            return `${this.config.host}/socialite/${service}/callback`
        },
        async storeCredentials(service) {
            // Validate fields
            const isValid = await this.$refs[service].validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/admin/settings/social-service', {
                    client_id: this[service].credentials.client_id,
                    client_secret: this[service].credentials.client_secret,
                    service: service,
                })
                .then(() => {
                    // Commit credentials
                    this.$store.commit('SET_SOCIAL_LOGIN_CONFIGURED', service)

                    this[service].allowedService = true
                    this[service].isVisibleCredentialsForm = false

                    // Show toaster
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.credentials_set', {
                            service: service,
                        }),
                    })
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.isError = true
                        this.errorMessage = error.response.data.message
                    }
                })
                .finally(() => (this.isLoading = false))
        },
    },
    created() {
        this.facebook.allowedService = this.config.allowedFacebookLogin
        this.google.allowedService = this.config.allowedGoogleLogin
        this.github.allowedService = this.config.allowedGithubLogin

        this.app.userRegistration = this.config.userRegistration
        this.app.userVerification = this.config.userVerification
    },
}
</script>
