<template>
    <AuthContentWrapper ref="auth">
        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true" class="mt-6 mb-12 !max-w-2xl">
            <Headline
                class="mx-auto !mb-10 max-w-screen-sm"
                title="Setup Wizard"
                description="Set up your application appearance, analytics, etc."
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

            <ValidationObserver @submit.prevent="appSetupSubmit" ref="appSetup" v-slot="{ invalid }" tag="form">
                <div class="card text-left shadow-card">
                    <FormLabel>General Settings</FormLabel>

                    <ValidationProvider tag="div" mode="passive" name="App Title" rules="required" v-slot="{ errors }">
                        <AppInputText title="App Title" :error="errors[0]">
                            <input
                                class="focus-border-theme input-dark"
                                v-model="app.title"
                                placeholder="Type your app title"
                                type="text"
                                :class="{ '!border-rose-600': errors[0] }"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="App Description"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText title="App Description" :error="errors[0]" :is-last="true">
                            <textarea
                                class="focus-border-theme input-dark"
                                v-model="app.description"
                                placeholder="Type your app description"
                                type="text"
                                :class="{ '!border-rose-600': errors[0] }"
                            ></textarea>
                        </AppInputText>
                    </ValidationProvider>
                </div>

                <div class="card text-left shadow-card">
                    <FormLabel>Appearance</FormLabel>

                    <ValidationProvider tag="div" mode="passive" name="Theme Color" v-slot="{ errors }">
                        <AppInputSwitch title="Color Theme">
                            <input v-model="app.color" type="color" />
                        </AppInputSwitch>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="App Logo" v-slot="{ errors }">
                        <AppInputText title="App Logo (optional)" :error="errors[0]">
                            <ImageInput v-model="app.logo" :error="errors[0]" />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="App Logo" v-slot="{ errors }">
                        <AppInputText title="App Logo Horizontal (optional)" :error="errors[0]">
                            <ImageInput v-model="app.logo_horizontal" :error="errors[0]" />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="App Favicon" v-slot="{ errors }">
                        <AppInputText title="App Favicon (optional)" :error="errors[0]">
                            <ImageInput v-model="app.favicon" :error="errors[0]" />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="App Favicon" v-slot="{ errors }">
                        <AppInputText
                            title="OG Image (optional)"
                            description="Image that appear when someone shares the content to Facebook or any other social medium. Preferred size is 1200x627"
                            :error="errors[0]"
                        >
                            <ImageInput v-model="app.og_image" :error="errors[0]" />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="App Favicon" v-slot="{ errors }">
                        <AppInputText
                            title="App Touch Icon (optional)"
                            description="If user store bookmark on his phone screen, this icon appear in app thumbnail. Preferred size is 156x156"
                            :error="errors[0]"
                            :is-last="true"
                        >
                            <ImageInput v-model="app.touch_icon" :error="errors[0]" />
                        </AppInputText>
                    </ValidationProvider>
                </div>

                <div class="card text-left shadow-card">
                    <FormLabel>Application</FormLabel>

                    <ValidationProvider tag="div" mode="passive" name="Contact Email" rules="required" v-slot="{ errors }">
                        <AppInputText title="Contact Email" :error="errors[0]">
                            <input
                                class="focus-border-theme input-dark"
                                v-model="app.contactMail"
                                placeholder="Type your contact email"
                                type="email"
								:class="{ '!border-rose-600': errors[0] }"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider tag="div" mode="passive" name="Google Analytics Code" v-slot="{ errors }">
                        <AppInputText title="Google Analytics Code (optional)" :error="errors[0]">
                            <input
                                class="focus-border-theme input-dark"
                                v-model="app.googleAnalytics"
                                placeholder="Paste your Google Analytics Code"
                                type="text"
                                :class="{ '!border-rose-600': errors[0] }"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <AppInputSwitch
                        title="Allow User Registration"
                        description="You can disable public registration for new users. You will still able to create new users in administration panel."
						:is-last="true"
                    >
                        <SwitchInput v-model="app.userRegistration" class="switch" :state="app.userRegistration" />
                    </AppInputSwitch>
                </div>

				<div class="card text-left shadow-card">
					<FormLabel>User Features</FormLabel>

					<AppInputSwitch
						title="Storage Limitation"
						description="If this value is off, all users will have infinity storage capacity and you won't be able to charge your users for storage plan."
					>
                        <SwitchInput v-model="app.storageLimitation" :state="app.storageLimitation" />
                    </AppInputSwitch>

                    <ValidationProvider
						tag="div"
						mode="passive"
						name="Default Storage Space"
						rules="required"
						v-slot="{ errors }"
					>
                        <AppInputText
							v-if="app.storageLimitation"
							title="Default Storage Space for Accounts"
							:error="errors[0]"
						>
                            <input
								class="focus-border-theme input-dark"
								v-model="app.defaultStorage"
								min="1"
								max="999999999"
								placeholder="Set default storage space in GB"
								type="number"
								:class="{ '!border-rose-600': errors[0] }"
							/>
                        </AppInputText>
                    </ValidationProvider>

                    <ValidationProvider
						tag="div"
						mode="passive"
						name="Default Storage Space"
						rules="required"
						v-slot="{ errors }"
					>
                        <AppInputText
							v-if="app.teamsDefaultMembers"
							title="Max Team Members"
							description="Type -1 to set unlimited team members."
							:error="errors[0]"
							:is-last="true"
						>
                            <input
								class="focus-border-theme input-dark"
								v-model="app.teamsDefaultMembers"
								min="1"
								max="999999999"
								placeholder="Set default max team members"
								type="number"
								:class="{'!border-rose-600': errors[0]}"
							/>
                        </AppInputText>
                    </ValidationProvider>
				</div>

                <div class="card text-left shadow-card">
                    <FormLabel>Subscription</FormLabel>

                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Subscription Type"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText
                            :title="$t('Subscription Type')"
                            description="Choose your preferred subscription system in advance. After installation and any other user registration, you can't change this setting later."
							:error="errors[0]"
                        >
                            <SelectInput
                                v-model="app.subscriptionType"
                                :default="app.subscriptionType"
                                :options="$store.getters.subscriptionTypes"
                                :placeholder="$t('Select your subscription type')"
								:is-error="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <InfoBox class="!mb-2">
                        <p>Any other subscription related settings you will be able set up later in admin panel.</p>
                    </InfoBox>
                </div>

                <AuthButton
                    class="w-full justify-center"
                    icon="chevron-right"
                    text="Save and Create Admin"
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
import SwitchInput from '../../components/Inputs/SwitchInput'
import AppInputSwitch from '../../components/Forms/Layouts/AppInputSwitch'
import ImageInput from '../../components/Inputs/ImageInput'
import FormLabel from '../../components/UI/Labels/FormLabel'
import AppInputText from '../../components/Forms/Layouts/AppInputText'
import InfoBox from '../../components/UI/Others/InfoBox'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import { SettingsIcon } from 'vue-feather-icons'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { required } from 'vee-validate/dist/rules'
import axios from 'axios'

export default {
    name: 'EnvironmentSetup',
    components: {
        AppInputText,
        AppInputSwitch,
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
        Headline,
    },
    data() {
        return {
            isLoading: false,
            app: {
                color: '#00BC7E',
                subscriptionType: undefined,
                title: undefined,
                description: undefined,
                logo: undefined,
                logo_horizontal: undefined,
                favicon: undefined,
                og_image: undefined,
                touch_icon: undefined,
                contactMail: undefined,
                googleAnalytics: undefined,
                defaultStorage: 5,
				teamsDefaultMembers: 5,
                userRegistration: 1,
                storageLimitation: 1,
            },
        }
    },
    methods: {
        async appSetupSubmit() {
            if (this.$root.$data.config.isSetupWizardDemo) {
                this.$router.push({ name: 'AdminAccount' })
            }

            // Validate fields
            const isValid = await this.$refs.appSetup.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Create form
            let formData = new FormData()

            // Add image to form
            formData.append('color', this.app.color)
            formData.append('title', this.app.title)
            formData.append('description', this.app.description)
            formData.append('contactMail', this.app.contactMail)
            formData.append('userRegistration', Boolean(this.app.userRegistration) ? 1 : 0)
            formData.append('storageLimitation', Boolean(this.app.storageLimitation) ? 1 : 0)

            if (this.app.subscriptionType) formData.append('subscriptionType', this.app.subscriptionType)

			if (this.app.googleAnalytics) formData.append('googleAnalytics', this.app.googleAnalytics)

            if (this.app.defaultStorage) formData.append('defaultStorage', this.app.defaultStorage)
            if (this.app.teamsDefaultMembers) formData.append('teamsDefaultMembers', this.app.teamsDefaultMembers)

            if (this.app.logo) formData.append('logo', this.app.logo)

            if (this.app.logo_horizontal) formData.append('logo_horizontal', this.app.logo_horizontal)

            if (this.app.og_image) formData.append('og_image', this.app.og_image)

            if (this.app.touch_icon) formData.append('touch_icon', this.app.touch_icon)

            if (this.app.favicon) formData.append('favicon', this.app.favicon)

            // Send request to get verify account
            axios
                .post('/api/setup/app-setup', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(() => {

                    // Redirect to next step
                    this.$router.push({ name: 'AdminAccount' })
                })
                .finally((error) => {
                    this.isLoading = false
                })
        },
    },
    created() {
        this.$scrollTop()

        if (this.$root.$data.config.isSetupWizardDebug) {
            this.app.subscriptionType = 'metered'
            this.app.title = 'VueFileManager'
            this.app.description = 'Your private cloud storage software build on Laravel & Vue.js.'
            this.app.contactMail = 'howdy@hi5ve.digital'
            this.app.googleAnalytics = 'UA-123456789'
            this.app.defaultStorage = '5'
            this.app.userRegistration = 1
            this.app.storageLimitation = 1
            this.app.userVerification = 0
        }
    },
}
</script>
