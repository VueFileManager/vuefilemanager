<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up your storage driver and email client.</h2>
            </div>

            <ValidationObserver @submit.prevent="EnvironmentSetupSubmit" ref="environmentSetup" v-slot="{ invalid }" tag="form" class="form block-form">
                <InfoBox>
                    <p>If you don’t know which storage driver set, keep selected <b>'Local Driver'</b>. For more info, where
                        you can host your files <a href="https://vuefilemanager.com/docs/guide/storage.html#introduction" target="_blank">visit our guide</a>.</p>
                </InfoBox>

                <FormLabel>Storage Setup</FormLabel>

                <div class="block-wrapper">
                    <label>Storage Service:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage Service" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="storage.driver" :options="storageServiceList" default="local" placeholder="Select your storage service" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="storage-additionals" v-if="storage.driver !== 'local'">
                    <div class="block-wrapper">
                        <label>Key:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Key" rules="required" v-slot="{ errors }">
                            <input v-model="storage.key" placeholder="Paste your key" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Secret:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Secret" rules="required" v-slot="{ errors }">
                            <input v-model="storage.secret" placeholder="Paste your secret" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Region:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Region" rules="required" v-slot="{ errors }">
                            <SelectInput v-model="storage.region" :options="regionList" :key="storage.driver" placeholder="Select your region" :isError="errors[0]"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            <small class="input-help">
                                Select your region where is your bucket/space created.
                            </small>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper" v-if="storage.driver !== 's3'">
                        <label>Endpoint URL:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Endpoint" rules="required" v-slot="{ errors }">
                            <input v-model="storage.endpoint" placeholder="Type your endpoint" type="text" :class="{'is-error': errors[0]}" readonly/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Bucket:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Bucket" rules="required" v-slot="{ errors }">
                            <input v-model="storage.bucket" placeholder="Type your bucket name" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            <small class="input-help">
                                Provide your created unique bucket name
                            </small>
                        </ValidationProvider>
                    </div>

                    <InfoBox>
                        <p>Later, you can edit these data in your <b>.env</b> file which is located in app root folder.</p>
                    </InfoBox>
                </div>

                <FormLabel class="mt-70">Email Setup</FormLabel>

                <div class="block-wrapper">
                    <label>Mail Driver:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Driver" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="mail.driver" :options="mailDriverList" default="smtp" placeholder="Select your mail driver" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Host:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Host" rules="required" v-slot="{ errors }">
                        <input v-model="mail.host" placeholder="Type your mail host" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Port:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Port" rules="required" v-slot="{ errors }">
                        <input v-model="mail.port" placeholder="Type your mail port" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Username:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Username" rules="required" v-slot="{ errors }">
                        <input v-model="mail.username" placeholder="Type your mail username" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Password" rules="required" v-slot="{ errors }">
                        <input v-model="mail.password" placeholder="Type your mail password" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Encryption:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Encryption" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="mail.encryption" :options="encryptionList" placeholder="Select your mail encryption" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Save and Set General Settings" :loading="isLoading" :disabled="isLoading"/>
                </div>

            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
import SelectInput from '@/components/Others/Forms/SelectInput'
import FormLabel from '@/components/Others/Forms/FormLabel'
import InfoBox from '@/components/Others/Forms/InfoBox'
import AuthContent from '@/components/Auth/AuthContent'
import AuthButton from '@/components/Auth/AuthButton'
import {SettingsIcon} from 'vue-feather-icons'
import {required} from 'vee-validate/dist/rules'
import {mapGetters} from 'vuex'
import axios from 'axios'

export default {
    name: 'EnvironmentSetup',
    components: {
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        SettingsIcon,
        SelectInput,
        AuthContent,
        AuthButton,
        FormLabel,
        required,
        InfoBox,
    },
    watch: {
        'storage.driver': function () {
            this.storage.region = ''
        },
        'storage.region': function (val) {
            if (this.storage.driver === 'spaces')
                this.storage.endpoint = 'https://' + val + '.digitaloceanspaces.com'

            if (this.storage.driver === 'wasabi')
                this.storage.endpoint = 'https://s3.' + val + '.wasabisys.com'

            if (this.storage.driver === 'backblaze')
                this.storage.endpoint = 'https://s3.' + val + '.backblazeb2.com'
        },
    },
    computed: {
        regionList() {
            switch (this.storage.driver) {
                case 's3':
                    return this.s3Regions
                    break
                case 'spaces':
                    return this.digitalOceanRegions
                    break
                case 'wasabi':
                    return this.wasabiRegions
                    break
                case 'backblaze':
                    return this.backblazeRegions
                    break
            }
        },
    },
    data() {
        return {
            isLoading: false,
            wasabiRegions: [
                {
                    label: 'US East 1 (N. Virginia)',
                    value: 'us-east-1',
                },
                {
                    label: 'US East 2 (N. Virginia)',
                    value: 'us-east-2',
                },
                {
                    label: 'US West 1 (Oregon)',
                    value: 'us-west-1',
                },
                {
                    label: 'EU Central 1 (Amsterdam)',
                    value: 'eu-central-1',
                },
            ],
            backblazeRegions: [
                {
                    label: 'us-west-001',
                    value: 'us-west-001',
                },
                {
                    label: 'us-west-002',
                    value: 'us-west-002',
                },
                {
                    label: 'eu-central-003',
                    value: 'eu-central-003',
                },
            ],
            digitalOceanRegions: [
                {
                    label: 'New York',
                    value: 'nyc3',
                },
                {
                    label: 'San Francisco',
                    value: 'sfo2',
                },
                {
                    label: 'Amsterdam',
                    value: 'ams3',
                },
                {
                    label: 'Singapore',
                    value: 'sgp1',
                },
                {
                    label: 'Frankfurt',
                    value: 'fra1',
                },
            ],
            s3Regions: [
                {
                    label: 'us-east-1',
                    value: 'us-east-1',
                },
                {
                    label: 'us-east-2',
                    value: 'us-east-2',
                },
                {
                    label: 'us-west-1',
                    value: 'us-west-1',
                },
                {
                    label: 'us-west-2',
                    value: 'us-west-2',
                },
                {
                    label: 'af-south-1',
                    value: 'af-south-1',
                },
                {
                    label: 'ap-east-1',
                    value: 'ap-east-1',
                },
                {
                    label: 'ap-south-1',
                    value: 'ap-south-1',
                },
                {
                    label: 'ap-northeast-2',
                    value: 'ap-northeast-2',
                },
                {
                    label: 'ap-southeast-1',
                    value: 'ap-southeast-1',
                },
                {
                    label: 'ap-southeast-2',
                    value: 'ap-southeast-2',
                },
                {
                    label: 'ap-northeast-1',
                    value: 'ap-northeast-1',
                },
                {
                    label: 'ca-central-1',
                    value: 'ca-central-1',
                },
                {
                    label: 'eu-central-1',
                    value: 'eu-central-1',
                },
                {
                    label: 'eu-west-1',
                    value: 'eu-west-1',
                },
                {
                    label: 'eu-west-2',
                    value: 'eu-west-2',
                },
                {
                    label: 'eu-south-1',
                    value: 'eu-south-1',
                },
                {
                    label: 'eu-west-3',
                    value: 'eu-west-3',
                },
                {
                    label: 'eu-north-1',
                    value: 'eu-north-1',
                },
                {
                    label: 'me-south-1',
                    value: 'me-south-1',
                },
                {
                    label: 'sa-east-1',
                    value: 'sa-east-1',
                },
            ],
            storageServiceList: [
                {
                    label: 'Local Driver',
                    value: 'local',
                },
                {
                    label: 'Amazon Web Services S3',
                    value: 's3',
                },
                {
                    label: 'Digital Ocean Spaces',
                    value: 'spaces',
                },
                {
                    label: 'Object Cloud Storage by Wasabi',
                    value: 'wasabi',
                },
                {
                    label: 'Backblaze B2 Cloud Storage',
                    value: 'backblaze',
                },
            ],
            encryptionList: [
                {
                    label: 'TLS',
                    value: 'tls',
                },
                {
                    label: 'SSL',
                    value: 'ssl',
                },
            ],
            mailDriverList: [
                {
                    label: 'smtp',
                    value: 'smtp',
                },
                {
                    label: 'sendmail',
                    value: 'sendmail',
                },
                {
                    label: 'mailgun',
                    value: 'mailgun',
                },
                {
                    label: 'ses',
                    value: 'ses',
                },
                {
                    label: 'postmark',
                    value: 'postmark',
                },
                {
                    label: 'log',
                    value: 'log',
                },
                {
                    label: 'array',
                    value: 'array',
                },
            ],
            storage: {
                driver: 'local',
                key: '',
                secret: '',
                endpoint: '',
                region: '',
                bucket: '',
            },
            mail: {
                driver: 'smtp',
                host: '',
                port: '',
                username: '',
                password: '',
                encryption: '',
            }
        }
    },
    methods: {
        async EnvironmentSetupSubmit() {

            // Validate fields
            const isValid = await this.$refs.environmentSetup.validate();

            if (!isValid) return;

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/setup/environment-setup', {
                    storage: this.storage,
                    mail: this.mail,
                })
                .then(response => {

                    // End loading
                    this.isLoading = false

                    // Redirect to next step
                    this.$router.push({name: 'AppSetup'})
                })
                .catch(error => {

                    // End loading
                    this.isLoading = false
                })
        },
    },
    created() {
        this.$scrollTop()
    }
}
</script>

<style scoped lang="scss">
@import '@assets/vue-file-manager/_forms';
@import '@assets/vue-file-manager/_auth';
@import '@assets/vue-file-manager/_setup_wizard';
</style>
