<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up your application appearance, analytics, etc.</h2>
            </div>

            <ValidationObserver @submit.prevent="appSetupSubmit" ref="appSetup" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>General Settings</FormLabel>

                <div class="block-wrapper">
                    <label>Mail Driver:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Driver" rules="required" v-slot="{ errors }">
                        <input v-model="mail.driver" placeholder="Type your mail driver" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>


                <FormLabel class="mt-70">Others Information</FormLabel>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Save and Create Admin" :loading="isLoading" :disabled="isLoading"/>
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
            async appSetupSubmit() {
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
