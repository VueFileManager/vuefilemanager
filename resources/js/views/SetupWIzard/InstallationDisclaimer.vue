<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Database was installed successfully. Let's set up application, Make sure you have these informations before continue:</h2>
            </div>

            <div id="loader" v-if="isLoading">
                <Spinner></Spinner>
            </div>

            <div class="form block-form" v-if="! isLoading">
                <InfoBox>
                    <ul v-if="isExtended" style="margin-top: 0" class="information-list">
                        <li>
                            1. Stripe API Credentials
                        </li>
                        <li>
                            2. Billing details for Stripe Subscription Service
                        </li>
                        <li>
                            3. You will create your subscription plans
                        </li>
                        <li>
                            4. Email Account Credentials for sending emails to your users
                        </li>
                        <li>
                            5. If you use external storage service, then you will need set your API credentials
                        </li>
                        <li>
                            6. Some general settings for VueFileManager like Google Analytics, logo, favicon and application name
                        </li>
                        <li>
                            7. You will create admin account
                        </li>
                    </ul>
                    <ul v-else style="margin-top: 0" class="information-list">
                        <li>
                            1. Email Account Credentials for sending emails to your users
                        </li>
                        <li>
                            2. If you use external storage service, then you will need set your API credentials
                        </li>
                        <li>
                            3. Some general settings for VueFileManager like Google Analytics, logo, favicon and application name
                        </li>
                        <li>
                            4. You will create admin account
                        </li>
                    </ul>
                </InfoBox>

                <router-link v-if="isExtended" :to="{name: 'SubscriptionService'}" tag="div" class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="I Get It! Let's Set Up Application" :loading="isLoading" :disabled="isLoading"/>
                </router-link>

                <router-link v-if="! isExtended" :to="{name: 'EnvironmentSetup'}" tag="div" class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="I Get It! Let's Set Up Application" :loading="isLoading" :disabled="isLoading"/>
                </router-link>
            </div>
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
    import Spinner from '@/components/FilesView/Spinner'
    import { SettingsIcon } from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'InstallationDisclaimer',
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
            Spinner,
            InfoBox,
        },
        data() {
            return {
                isLoading: true,
                isError: false,
                isExtended: undefined
            }
        },
        created() {

            // Send request to get verify account
            axios
                .post('/api/setup/purchase-code', {
                    purchaseCode: localStorage.getItem('purchase_code'),
                })
                .then(response => {

                    this.$scrollTop()

                    // End loading
                    this.isLoading = false

                    if (response.data === 'b6896a44017217c36f4a6fdc56699728') {
                        this.isExtended = true
                        localStorage.setItem('license', 'Extended')
                    } else {
                        this.isExtended = false
                        localStorage.setItem('license', 'Regular')
                    }
                })
                .catch(error => {

                    // End loading
                    this.isLoading = false

                    if (error.response.status == 400) {
                        this.$router.push({name: 'PurchaseCode'})
                    }
                })
        }
    }
</script>

<style scoped lang="scss">
    //@import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';

    #loader {
        position: relative;
        margin-top: 80px;
    }

    .information-list {
        li {
            padding: 8px 0;
            @include font-size(17);
            font-weight: 600;

            &:first-child {
                padding-top: 0;
            }

            &:last-child {
                padding-bottom: 0;
            }
        }
    }
</style>
