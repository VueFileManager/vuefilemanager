<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up your storage driver and email client.</h2>
            </div>

            <ValidationObserver @submit.prevent="EnvironmentSetupSubmit" ref="stripeCredentials" v-slot="{ invalid }" tag="form" class="form block-form">
                <InfoBox>
                    <p>If you don’t know which storage set, select <b>'Local Driver'</b>. For more info, where
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
                            <input v-model="storage.key" placeholder="Paste your key" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Secret:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Secret" rules="required" v-slot="{ errors }">
                            <input v-model="storage.secret" placeholder="Paste your secret" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Endpoint:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Endpoint" rules="required" v-slot="{ errors }">
                            <input v-model="storage.endpoint" placeholder="Type your endpoint" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Region:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Region" rules="required" v-slot="{ errors }">
                            <input v-model="storage.region" placeholder="Type your region" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>Bucket:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Bucket" rules="required" v-slot="{ errors }">
                            <input v-model="storage.bucket" placeholder="Type your bucket name" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
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
                        <input v-model="mail.driver" placeholder="Type your mail driver" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Host:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Host" rules="required" v-slot="{ errors }">
                        <input v-model="mail.host" placeholder="Type your mail host" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Port:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Port" rules="required" v-slot="{ errors }">
                        <input v-model="mail.port" placeholder="Type your mail port" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Username:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Username" rules="required" v-slot="{ errors }">
                        <input v-model="mail.username" placeholder="Type your mail username" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Password" rules="required" v-slot="{ errors }">
                        <input v-model="mail.password" placeholder="Type your mail password" type="text" />
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
                    <AuthButton icon="chevron-right" text="Save and Set Billings" :loading="isLoading" :disabled="isLoading"/>
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
    import { SettingsIcon } from 'vue-feather-icons'
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
        data() {
            return {
                isLoading: false,
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
                storage: {
                    driver: 'local',
                    key: '',
                    secret: '',
                    endpoint: '',
                    region: '',
                    bucket: '',
                },
                mail: {
                    driver: '',
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
                this.$router.push({name: 'AppSetup'})
            },
        },
        created() {
            var container = document.getElementById('vue-file-manager')
            container.scrollTop = 0
        }
    }
</script>

<style scoped lang="scss">
    //@import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';
</style>
