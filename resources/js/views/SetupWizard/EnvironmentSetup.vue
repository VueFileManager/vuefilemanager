<template>
    <AuthContentWrapper ref="auth">
        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true" class="mt-6 mb-12 !max-w-2xl">
            <Headline
                class="mx-auto !mb-10 max-w-screen-sm"
                title="Setup Wizard"
                description="Set up your storage driver and email client."
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

            <ValidationObserver
                @submit.prevent="EnvironmentSetupSubmit"
                ref="environmentSetup"
                v-slot="{ invalid }"
                tag="form"
            >
                <StorageSetup v-model="storage" class="card text-left shadow-card" />

                <MailSetup v-model="mail" class="card text-left shadow-card" />

				<BroadcastSetup v-model="broadcast" class="card text-left shadow-card" />

                <div class="card text-left shadow-card">
                    <FormLabel>Environment Setup</FormLabel>

                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Environment"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText title="Environment" :error="errors[0]" :is-last="true">
                            <SelectInput
                                v-model="environment"
                                :options="environmentSetupList"
                                default="production"
                                placeholder="Select your environment setup"
                                :isError="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>
                </div>

                <InfoBox v-if="isError" type="error" class="!mb-5">
                    <p>Whooops, something went wrong, please try it again.</p>
                </InfoBox>

                <AuthButton
                    class="w-full justify-center"
                    icon="chevron-right"
                    text="Save and Set General Settings"
                    :loading="isLoading"
                    :disabled="isLoading"
                />
            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import SelectInput from '../../components/Inputs/SelectInput'
import FormLabel from '../../components/UI/Labels/FormLabel'
import StorageSetup from '../../components/Forms/StorageSetup'
import AppInputText from '../../components/Forms/Layouts/AppInputText'
import InfoBox from '../../components/UI/Others/InfoBox'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import MailSetup from '../../components/Forms/MailSetup'
import { required } from 'vee-validate/dist/rules'
import { SettingsIcon } from 'vue-feather-icons'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { events } from '../../bus'
import axios from 'axios'
import BroadcastSetup from "../../components/Forms/BroadcastSetup";

export default {
    name: 'EnvironmentSetup',
    components: {
		BroadcastSetup,
        MailSetup,
        StorageSetup,
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        AppInputText,
        SettingsIcon,
        SelectInput,
        AuthContent,
        AuthButton,
        FormLabel,
        required,
        InfoBox,
        Headline,
    },
    data() {
        return {
            isError: false,
            isLoading: false,
            environment: 'production',
            environmentSetupList: [
                {
                    label: 'Production',
                    value: 'production',
                },
                {
                    label: 'Dev',
                    value: 'local',
                },
            ],
            storage: undefined,
            mail: undefined,
            broadcast: undefined,
        }
    },
    methods: {
        async EnvironmentSetupSubmit() {
            if (this.$root.$data.config.isSetupWizardDemo) {
                this.$router.push({ name: 'AppSetup' })
                return
            }

            // Validate fields
            const isValid = await this.$refs.environmentSetup.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/setup/environment-setup', {
                    broadcast: this.broadcast,
                    environment: this.environment,
                    storage: this.storage,
                    mailDriver: this.mail.driver,
                    smtp: this.mail.smtp,
                    mailgun: this.mail.mailgun,
                    ses: this.mail.ses,
                    postmark: this.mail.postmark,
                })
                .then(() => {
                    // Redirect to next step
                    this.$router.push({ name: 'AppSetup' })
                })
                .catch((error) => {
					if ([401, 500].includes(error.response.status)) {
						events.$emit('alert:open', {
							title: error.response.data.title || 'Whooops, something went wrong!',
							message: error.response.data.message,
						})
                    } else {
                        this.isError = true
                    }
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
    },
    beforeMount() {
        if (this.$root.$data.config.isSetupWizardDebug) {
            this.environment = 'local'
            this.storage.driver = 'local'

            this.mail.driver = 'smtp'
            this.mail.smtp = {
                host: 'test.mail.com',
                port: 445,
                username: 'howdy@hi5ve.digital',
                password: 'root',
                encryption: 'ssl',
            }
        }
    },
    created() {
        this.$scrollTop()
    },
}
</script>
