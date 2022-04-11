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
				<StorageSetup v-model="storage" class="card shadow-card text-left" />

                <div class="card text-left shadow-card">
                    <FormLabel>Email Setup</FormLabel>

                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Mail Driver"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText
                            title="Mail Driver"
                            :error="errors[0]"
                            :is-last="!mailDriver || mailDriver === 'log'"
                        >
                            <SelectInput
                                v-model="mailDriver"
                                :default="mailDriver"
                                :options="mailDriverList"
                                placeholder="Select your mail driver"
                                :isError="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <div v-if="mailDriver === 'smtp'">
                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Mail Host"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Mail Host" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="smtp.host"
                                    placeholder="Type your mail host"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Mail Port"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Mail Port" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="smtp.port"
                                    placeholder="Type your mail port"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Mail Username"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Mail Username" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model.lazy="smtp.username"
                                    placeholder="Type your mail username"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Mail Password"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Mail Password" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="smtp.password"
                                    placeholder="Type your mail password"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Mail Encryption"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Mail Encryption" :error="errors[0]" :is-last="! shouldSetSMTPEmail">
                                <SelectInput
                                    v-model="smtp.encryption"
                                    :default="smtp.encryption"
                                    :options="mailEncryptionList"
                                    placeholder="Select your mail encryption"
                                    :isError="errors[0]"
                                />
                            </AppInputText>
                        </ValidationProvider>

						<ValidationProvider
							v-if="shouldSetSMTPEmail"
							tag="div"
							mode="passive"
							name="Mail From Address"
							rules="required|email"
							v-slot="{ errors }"
						>
                            <AppInputText title="Mail" :error="errors[0]" :is-last="true">
                                <input
									class="focus-border-theme input-dark"
									v-model.lazy="smtp.email"
									placeholder="Type your mail from address"
									type="text"
									:class="{ '!border-rose-600': errors[0] }"
								/>
                            </AppInputText>
                        </ValidationProvider>
                    </div>

                    <div v-if="mailDriver === 'mailgun'">
                        <ValidationProvider tag="div" mode="passive" name="Domain" rules="required" v-slot="{ errors }">
                            <AppInputText title="Domain" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="mailgun.domain"
                                    placeholder="Type your domain"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                            <AppInputText title="Secret" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="mailgun.secret"
                                    placeholder="Type your secret"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

						<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
							<AppInputText title="Sender (Email)" :error="errors[0]">
								<input
									class="focus-border-theme input-dark"
									v-model="mailgun.sender"
									placeholder="Type your sender email..."
									type="text"
									:class="{ '!border-rose-600': errors[0] }"
								/>
							</AppInputText>
						</ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Endpoint"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Endpoint" :error="errors[0]" :is-last="true">
								<SelectInput
									v-model="mailgun.endpoint"
									:options="mailgunRegions"
									placeholder="Select your endpoint"
									:isError="errors[0]"
								/>
                            </AppInputText>
                        </ValidationProvider>
                    </div>

                    <div v-if="mailDriver === 'postmark'">
                        <ValidationProvider tag="div" mode="passive" name="Token" rules="required" v-slot="{ errors }">
                            <AppInputText title="Token" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="postmark.token"
                                    placeholder="Type your token"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

						<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
							<AppInputText title="Sender Signature (Email)" :is-last="true" :error="errors[0]">
								<input
									class="focus-border-theme input-dark"
									v-model="postmark.sender"
									placeholder="Type your sender signature..."
									type="text"
									:class="{ '!border-rose-600': errors[0] }"
								/>
							</AppInputText>
						</ValidationProvider>
                    </div>

                    <div v-if="mailDriver === 'ses'">
                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Access Key"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Access Key" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="ses.access_key"
                                    placeholder="Type your access key"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Secret Access Key"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Secret Access Key" :error="errors[0]">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="ses.secret_access_key"
                                    placeholder="Type your secret access key"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Default Region"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText title="Region" :error="errors[0]">
								<SelectInput
									v-model="ses.default_region"
									:options="s3Regions"
									placeholder="Select your region"
									:isError="errors[0]"
								/>
                            </AppInputText>
                        </ValidationProvider>

						<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
							<AppInputText title="Identity (Email)" :error="errors[0]">
								<input
									class="focus-border-theme input-dark"
									v-model="ses.sender"
									placeholder="Type your identity email..."
									type="text"
									:class="{ '!border-rose-600': errors[0] }"
								/>
							</AppInputText>
						</ValidationProvider>

                        <ValidationProvider tag="div" mode="passive" name="Session Token" v-slot="{ errors }">
                            <AppInputText title="Session Token (optional)" :error="errors[0]" :is-last="true">
                                <input
                                    class="focus-border-theme input-dark"
                                    v-model="ses.session_token"
                                    placeholder="Type your session token"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                />
                            </AppInputText>
                        </ValidationProvider>
                    </div>
                </div>

				<div class="card text-left shadow-card">
					<FormLabel icon="wifi">
						{{ $t('Broadcasting') }}
					</FormLabel>

					<ValidationProvider tag="div" mode="passive" name="Broadcast Driver" rules="required" v-slot="{ errors }">
						<AppInputText title="Broadcast Driver" :error="errors[0]" :is-last="broadcast.driver === 'none' || broadcast.driver === undefined">
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

						<ValidationProvider tag="div" mode="passive" name="Cluster" rules="required" v-slot="{ errors }">
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
import AppInputText from '../../components/Admin/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import SelectInput from '../../components/Others/Forms/SelectInput'
import FormLabel from '../../components/Others/Forms/FormLabel'
import InfoBox from '../../components/Others/Forms/InfoBox'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import { SettingsIcon } from 'vue-feather-icons'
import Headline from '../Auth/Headline'
import { required } from 'vee-validate/dist/rules'
import axios from 'axios'
import { mapGetters } from 'vuex'
import {events} from "../../bus";
import StorageSetup from "../../components/Setup/StorageSetup";

export default {
    name: 'EnvironmentSetup',
    components: {
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
    watch: {
		'smtp.username': function (val) {
			if (this.$isValidEmail(val)) {
				this.smtp.email = undefined
				this.shouldSetSMTPEmail = false
			} else {
				this.shouldSetSMTPEmail = true
			}
		},
    },
    computed: {
        ...mapGetters([
			'mailEncryptionList',
			'mailDriverList',
		]),
    },
    data() {
        return {
			shouldSetSMTPEmail: false,
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
            mailDriver: undefined,
            ses: {
                access_key: undefined,
                secret_access_key: undefined,
                default_region: undefined,
                session_token: undefined,
				sender: undefined,
			},
            smtp: {
                host: undefined,
                port: undefined,
				email: undefined,
				username: undefined,
                password: undefined,
                encryption: undefined,
            },
            mailgun: {
                domain: undefined,
                secret: undefined,
                endpoint: undefined,
				sender: undefined,
            },
            postmark: {
                token: undefined,
				sender: undefined,
			},
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
			mailgunRegions: [
				{
					label: 'US Endpoint (api.mailgun.net)',
					value: 'api.mailgun.net',
				},
				{
					label: 'EU Endpoint (api.eu.mailgun.net)',
					value: 'api.eu.mailgun.net',
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
                    mailDriver: this.mailDriver,
                    smtp: this.smtp,
                    mailgun: this.mailgun,
                    ses: this.ses,
                    postmark: this.postmark,
                })
                .then(() => {
                    // Redirect to next step
                    this.$router.push({ name: 'AppSetup' })
                })
                .catch((error) => {
					if (error.response.status === 401 && error.response.data.type === 's3-connection-error') {
						events.$emit('alert:open', {
							title: 'S3 Connection Error - Wrong Credentials or Not Permitted',
							message: error.response.data.message,
						})
					} else if (error.response.status === 401 && error.response.data.type === 'mailer-connection-error') {
						events.$emit('alert:open', {
							title: 'Mailer Connection Error - Wrong Credentials',
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
            this.mailDriver = 'smtp'
            this.environment = 'local'
            this.storage.driver = 'local'

            this.smtp = {
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
