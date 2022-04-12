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

                <div class="card text-left shadow-card">
                    <FormLabel icon="wifi">
                        {{ $t('Broadcasting') }}
                    </FormLabel>

                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Broadcast Driver"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText
                            title="Broadcast Driver"
                            :error="errors[0]"
                            :is-last="broadcast.driver === 'none' || broadcast.driver === undefined"
                        >
                            <SelectInput
                                v-model="broadcast.driver"
                                :options="broadcastDrivers"
                                placeholder="Select your broadcast driver"
                                :isError="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <div v-if="broadcast.driver === 'native'">
                        <ValidationProvider tag="div" mode="passive" name="Host" rules="required" v-slot="{ errors }">
                            <AppInputText title="Hostname or IP" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="broadcast.host"
                                    placeholder="Type your hostname or IP"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider tag="div" mode="passive" name="Port" rules="required" v-slot="{ errors }">
                            <AppInputText title="Port" :error="errors[0]" :is-last="true">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="broadcast.port"
                                    placeholder="Type your port"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>
                    </div>

                    <div v-if="broadcast.driver === 'pusher'">
                        <ValidationProvider tag="div" mode="passive" name="App ID" rules="required" v-slot="{ errors }">
                            <AppInputText title="App ID" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="broadcast.id"
                                    placeholder="Type your app id"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider tag="div" mode="passive" name="Key" rules="required" v-slot="{ errors }">
                            <AppInputText title="Key" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="broadcast.key"
                                    placeholder="Paste your key"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                            <AppInputText title="Secret" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="broadcast.secret"
                                    placeholder="Paste your secret"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Cluster"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Cluster" :error="errors[0]" :is-last="true">
                                <SelectInput
                                    v-model="broadcast.cluster"
                                    :options="pusherClusters"
                                    placeholder="Select your cluster"
                                    :isError="errors[0]"
                                />
                            </AppInputText>
                        </ValidationProvider>
                    </div>
                </div>

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
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import SelectInput from '../../components/Others/Forms/SelectInput'
import FormLabel from '../../components/Others/Forms/FormLabel'
import StorageSetup from '../../components/Setup/StorageSetup'
import AppInputText from '../../components/Admin/AppInputText'
import InfoBox from '../../components/Others/Forms/InfoBox'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import MailSetup from '../../components/Setup/MailSetup'
import { required } from 'vee-validate/dist/rules'
import { SettingsIcon } from 'vue-feather-icons'
import Headline from '../Auth/Headline'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'EnvironmentSetup',
    components: {
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
            broadcast: {
                driver: undefined,
                id: undefined,
                key: undefined,
                secret: undefined,
                cluster: undefined,
                port: undefined,
                host: undefined,
            },
            broadcastDrivers: [
                {
                    label: 'Pusher',
                    value: 'pusher',
                },
                {
                    label: 'VueFileManager Broadcast Server',
                    value: 'native',
                },
                {
                    label: 'None',
                    value: 'none',
                },
            ],
            pusherClusters: [
                {
                    label: 'US East (N. Virginia)',
                    value: 'mt1',
                },
                {
                    label: 'Asia Pacific (Singapore)',
                    value: 'ap1',
                },
                {
                    label: 'Asia Pacific (Mumbai)',
                    value: 'ap2',
                },
                {
                    label: 'US East (Ohio)',
                    value: 'us2',
                },
                {
                    label: 'Asia Pacific (Tokyo)',
                    value: 'ap3',
                },
                {
                    label: 'US West (Oregon)',
                    value: 'us3',
                },
                {
                    label: 'Asia Pacific (Sydney)',
                    value: 'ap4',
                },
                {
                    label: 'EU (Ireland)',
                    value: 'eu',
                },
                {
                    label: 'South America (São Paulo)',
                    value: 'sa1',
                },
            ],
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
