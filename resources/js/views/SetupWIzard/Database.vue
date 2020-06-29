<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up your database credentials.</h2>
            </div>

            <ValidationObserver @submit.prevent="databaseCredentialsSubmit" ref="verifyPurchaseCode" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>Database Credentials</FormLabel>
                <InfoBox>
                    <p>Firstly, create your database credentials in your database client. For how to, here is Usefull resources:</p>
                    <ul>
                        <li>
                            <a href="#" target="_blank">1. cPanel - MySQL Database Wizard</a>
                            <a href="#" target="_blank">2. Plesk - Website databases</a>
                        </li>
                    </ul>
                </InfoBox>

                <div class="block-wrapper">
                    <label>Connection:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="connection" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="databaseCredentials.connection" :options="connectionList" default="mysql" placeholder="Select your database connection" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Host:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="connection" rules="required" v-slot="{ errors }">
                        <input v-model="databaseCredentials.host" placeholder="Type your database host" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Port:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="connection" rules="required" v-slot="{ errors }">
                        <input v-model="databaseCredentials.port" placeholder="Type your database port" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Database Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="connection" rules="required" v-slot="{ errors }">
                        <input v-model="databaseCredentials.name" placeholder="Select your database name" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Database Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="connection" rules="required" v-slot="{ errors }">
                        <input v-model="databaseCredentials.password" placeholder="Select your database password" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Test your Connection and Continue" :loading="isLoading" :disabled="isLoading"/>
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
        name: 'Database',
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
                connectionList: [
                    {
                        label: 'MySQL',
                        value: 'mysql',
                    },
                    {
                        label: 'SQLite',
                        value: 'sqlite',
                    },
                    {
                        label: 'PqSQL',
                        value: 'pgsql',
                    },
                    {
                        label: 'SQLSry',
                        value: 'sqlsrv',
                    },
                ],
                databaseCredentials: {
                    connection: '',
                    host: '',
                    port: '',
                    name: '',
                    password: '',
                }
            }
        },
        methods: {
            async databaseCredentialsSubmit() {
                this.$router.push({name: 'StripeCredentials'})
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
