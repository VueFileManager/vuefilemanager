<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Upgrade VueFileManager</h1>
                <h2>Please fill form bellow to upgrade VueFileManager.</h2>
            </div>

            <ValidationObserver @submit.prevent="appSetupSubmit" ref="appSetup" v-slot="{ invalid }" tag="form"
                                class="form block-form">
                <FormLabel>Set your License</FormLabel>

                <div class="block-wrapper">
                    <label>Purchase Code:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Purchase code" rules="required" v-slot="{ errors }">
                        <input v-model="app.purchase_code" placeholder="Paste your purchase code" type="text" :class="{'is-error': errors[0]}"/>
                        <a class="input-help" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
                            Where I can find purchase code?
                        </a>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">General Settings</FormLabel>

                <div class="block-wrapper">
                    <label>App Title:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                        <input v-model="app.title" placeholder="Type your app title" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Description:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                        <input v-model="app.description" placeholder="Type your app description" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Logo (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo" v-slot="{ errors }">
                        <ImageInput v-model="app.logo" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Logo Horizontal (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo" v-slot="{ errors }">
                        <ImageInput v-model="app.logo_horizontal" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Favicon (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
                        <ImageInput v-model="app.favicon" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">Others Information</FormLabel>

                <div class="block-wrapper">
                    <label>Contact Email:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Contact Email"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="app.contactMail" placeholder="Type your contact email" type="email" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Google Analytics Code (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Google Analytics Code"
                                        v-slot="{ errors }">
                        <input v-model="app.googleAnalytics" placeholder="Paste your Google Analytics Code"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Storage Limitation:</label>
                                <small class="input-help">If this value is off, all users will have infinity storage capacity and you won't be <br/>able to charge your users for storage plan.</small>
                            </div>
                            <SwitchInput v-model="app.storageLimitation" class="switch" :state="app.storageLimitation"/>
                        </div>
                    </div>
                </div>

                <div class="block-wrapper" v-if="app.storageLimitation">
                    <label>Default Storage Space for Accounts:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Default Storage Space" rules="required" v-slot="{ errors }">
                        <input v-model="app.defaultStorage"
                               min="1"
                               max="999999999"
                               placeholder="Set default storage space in GB"
                               type="number"
                               :class="{'is-error': errors[0]}"
                        />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Allow User Registration:</label>
                                <small class="input-help">You can disable public registration for new users. You will still able to <br/>create new users in administration panel.</small>
                            </div>
                            <SwitchInput v-model="app.userRegistration" class="switch" :state="app.userRegistration"/>
                        </div>
                    </div>
                </div>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Save and Upgrade" :loading="isLoading" :disabled="isLoading"/>
                </div>

            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
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
            SwitchInput,
            AuthContent,
            ImageInput,
            AuthButton,
            FormLabel,
            required,
            InfoBox,
        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                isLoading: false,
                app: {
                    license: undefined,
                    purchase_code: '',
                    title: '',
                    description: '',
                    logo: undefined,
                    logo_horizontal: undefined,
                    favicon: undefined,
                    contactMail: '',
                    googleAnalytics: '',
                    defaultStorage: '5',
                    userRegistration: 1,
                    storageLimitation: 1,
                },
            }
        },
        methods: {
            storeAppSetup() {
                // Create form
                let formData = new FormData()

                // Add image to form
                formData.append('purchase_code', this.app.purchase_code)
                formData.append('license', this.app.license)
                formData.append('title', this.app.title)
                formData.append('description', this.app.description)
                formData.append('contactMail', this.app.contactMail)
                formData.append('userRegistration', Boolean(this.app.userRegistration) ? 1 : 0)
                formData.append('storageLimitation', Boolean(this.app.storageLimitation) ? 1 : 0)

                if (this.app.googleAnalytics)
                    formData.append('googleAnalytics', this.app.googleAnalytics)

                if (this.app.defaultStorage)
                    formData.append('defaultStorage', this.app.defaultStorage)

                if (this.app.logo)
                    formData.append('logo', this.app.logo)

                if (this.app.logo_horizontal)
                    formData.append('logo_horizontal', this.app.logo_horizontal)

                if (this.app.favicon)
                    formData.append('favicon', this.app.favicon)

                // Send request to get verify account
                axios
                    .post('/api/upgrade/app', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Redirect to next step
                        this.$router.push({name: 'SignIn'})
                    })
                    .catch(error => {

                        // End loading
                        this.isLoading = false
                    })
            },
            async appSetupSubmit() {

                // Validate fields
                const isValid = await this.$refs.appSetup.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/setup/purchase-code', {
                        purchaseCode: this.app.purchase_code
                    })
                    .then(response => {

                        if (response.data === 'b6896a44017217c36f4a6fdc56699728') {
                            this.app.license = 'Extended'
                            this.$store.commit('SET_SAAS', true)
                        } else {
                            this.app.license = 'Regular'
                        }

                        this.storeAppSetup()
                    })
                    .catch(error => {

                        if (error.response.status == 400) {
                            // TODO: error message
                        }
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
        },
        created() {
            if (this.config.latest_upgrade === '1.7')
                this.$router.push({name: 'SignIn'})
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';
</style>
