<template>
    <AuthContentWrapper ref="auth">
        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true" class="mt-6 mb-12 !max-w-2xl">
            <Headline
                class="mx-auto !mb-10 max-w-screen-sm"
                title="Setup Wizard"
                description="Set up your database connection to install application database."
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

            <ValidationObserver
                @submit.prevent="databaseCredentialsSubmit"
                ref="verifyPurchaseCode"
                v-slot="{ invalid }"
                tag="form"
                class="card text-left shadow-card"
            >
                <FormLabel> Database Credentials </FormLabel>

                <InfoBox>
                    <p>
                        We strongly recommend use MySQL or MariaDB database. Create new database, set all privileges and
                        get credentials. For those who use cPanel or Plesk, here is useful resources:
                    </p>
                    <ul>
                        <li>
                            <a
                                href="https://www.inmotionhosting.com/support/edu/cpanel/create-database-2/"
                                target="_blank"
                                >1. cPanel - MySQL Database Wizard</a
                            >
                            <a href="https://docs.plesk.com/en-US/obsidian/customer-guide/65157/" target="_blank"
                                >2. Plesk - Website databases</a
                            >
                        </li>
                    </ul>
                </InfoBox>

                <ValidationProvider tag="div" mode="passive" name="Connection" rules="required" v-slot="{ errors }">
                    <AppInputText title="Connection" :error="errors[0]">
                        <SelectInput
                            v-model="databaseCredentials.connection"
                            :options="connectionList"
                            default="mysql"
                            placeholder="Select your database connection"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Host" rules="required" v-slot="{ errors }">
                    <AppInputText title="Host" :error="errors[0]">
                        <input
                            v-model="databaseCredentials.host"
                            class="focus-border-theme input-dark"
                            placeholder="Type your database host"
                            type="text"
                            :class="{'!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Port" rules="required" v-slot="{ errors }">
                    <AppInputText title="Port" :error="errors[0]">
                        <input
                            v-model="databaseCredentials.port"
                            class="focus-border-theme input-dark"
                            placeholder="Type your database port"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Database Name" rules="required" v-slot="{ errors }">
                    <AppInputText title="Database Name" :error="errors[0]">
                        <input
                            v-model="databaseCredentials.name"
                            class="focus-border-theme input-dark"
                            placeholder="Type your database name"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Database Username"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Database Username" :error="errors[0]">
                        <input
                            v-model="databaseCredentials.username"
                            class="focus-border-theme input-dark"
                            placeholder="Type your database name"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Database Password"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Database Password (optional)" :error="errors[0]" :is-last="true">
                        <input
                            v-model="databaseCredentials.password"
                            class="focus-border-theme input-dark"
                            placeholder="Type your database password"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <InfoBox v-if="isError" type="error" style="margin-bottom: 10px">
                    <p>We couldn't establish database connection. Please double check your database credentials.</p>
                    <br />
                    <p>Detailed error: {{ errorMessage }}</p>
                </InfoBox>
            </ValidationObserver>
            <AuthButton
                @click.native="databaseCredentialsSubmit"
                class="w-full justify-center"
                icon="chevron-right"
                :text="submitButtonText"
                :loading="isLoading"
                :disabled="isLoading"
            />
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import SelectInput from '../../components/Inputs/SelectInput'
import AppInputText from '../../components/Forms/Layouts/AppInputText'
import FormLabel from '../../components/UI/Labels/FormLabel'
import InfoBox from '../../components/UI/Others/InfoBox'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import { SettingsIcon } from 'vue-feather-icons'
import { required } from 'vee-validate/dist/rules'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'Database',
    components: {
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
    computed: {
        submitButtonText() {
            return this.isLoading ? 'Testing and Installing Database' : 'Test Connection and Install Database'
        },
    },
    data() {
        return {
            isLoading: false,
            isError: false,
            errorMessage: '',
            connectionList: [
                {
                    label: 'MySQL',
                    value: 'mysql',
                },
            ],
            databaseCredentials: {
                connection: 'mysql',
                host: '',
                port: '',
                name: '',
                username: '',
                password: '',
            },
        }
    },
    methods: {
        async databaseCredentialsSubmit() {
            if (this.$root.$data.config.isSetupWizardDemo) {
                this.$router.push({ name: 'EnvironmentSetup' })
                return
            }

            // Validate fields
            const isValid = await this.$refs.verifyPurchaseCode.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true
            this.isError = false

            // Send request to get verify account
            axios
                .post('/api/setup/database', this.databaseCredentials)
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    // Redirect to next step
                    this.$router.push({ name: 'EnvironmentSetup' })
                })
                .catch((error) => {
                    if ((error.response.status = 500)) {
                        this.isError = true
                        this.errorMessage = error.response.data.message
                    }

                    // End loading
                    this.isLoading = false
                })
        },
    },
    created() {
        this.$scrollTop()

        if (this.$root.$data.config.isSetupWizardDebug) {
            this.databaseCredentials = {
                connection: 'mysql',
                host: '127.0.0.1',
                port: '3306',
                name: 'vuefilemanager-v2',
                username: 'root',
                password: 'secret',
            }
        }
    },
}
</script>
