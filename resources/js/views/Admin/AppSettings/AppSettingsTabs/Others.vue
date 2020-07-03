<template>
    <PageTab class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>Others Settings</FormLabel>

                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Allow User Registration:</label>
                            </div>
                            <SwitchInput v-model="app.userRegistration" class="switch" :state="app.userRegistration"/>
                        </div>
                    </div>
                </div>

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
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import axios from 'axios'

    export default {
        name: 'AppOthers',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
            SwitchInput,
            SelectInput,
            ImageInput,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            PageTab,
            InfoBox,
        },
        data() {
            return {
                isLoading: false,
                app: {
                    contactMail: '',
                    googleAnalytics: '',
                    defaultStorage: '',
                    userRegistration: 1,
                    storageLimitation: 1,
                },
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
