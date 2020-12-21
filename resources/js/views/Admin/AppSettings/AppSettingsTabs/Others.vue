<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup v-if="app">
            <div class="form block-form">
                <FormLabel>
                    {{ $t('admin_settings.others.section_user') }}
                </FormLabel>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">
                                    {{ $t('admin_settings.others.storage_limit') }}:
                                </label>
                                <small class="input-help" v-html="$t('admin_settings.others.storage_limit_help')"></small>
                            </div>
                            <SwitchInput
                                    @input="$updateText('/settings', 'storage_limitation', app.storageLimitation)"
                                    v-model="app.storageLimitation"
                                    class="switch"
                                    :state="app.storageLimitation"
                            />
                        </div>
                    </div>
                </div>
                <div class="block-wrapper" v-if="app.storageLimitation">
                    <label>{{ $t('admin_settings.others.default_storage') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Default Storage Space" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'storage_default', app.defaultStorage)"
                               v-model="app.defaultStorage"
                               min="1"
                               max="999999999"
                               :placeholder="$t('admin_settings.others.default_storage_plac')"
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
                                <label class="input-label">
                                    {{ $t('admin_settings.others.allow_registration') }}:
                                </label>
                                <small class="input-help" v-html="$t('admin_settings.others.allow_registration_help')"></small>
                            </div>
                            <SwitchInput @input="$updateText('/settings', 'registration', app.userRegistration)"
                                         v-model="app.userRegistration"
                                         class="switch"
                                         :state="app.userRegistration"
                            />
                        </div>
                    </div>
                </div>

                <FormLabel class="mt-70">
                    {{ $t('admin_settings.others.section_others') }}
                </FormLabel>
                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.others.contact_email') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Contact Email"
                                        rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'contact_email', app.contactMail)" v-model="app.contactMail"
                               :placeholder="$t('admin_settings.others.contact_email_plac')" type="email" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.others.google_analytics') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Google Analytics Code"
                                        v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'google_analytics', app.googleAnalytics)" v-model="app.googleAnalytics"
                               :placeholder="$t('admin_settings.others.google_analytics_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.others.mimetypes_blacklist') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mimetypes Blacklist" v-slot="{ errors }">
                        <textarea rows="2" @input="$updateText('/settings', 'mimetypes_blacklist', app.mimetypesBlacklist)" v-model="app.mimetypesBlacklist" :placeholder="$t('admin_settings.others.mimetypes_blacklist_plac')" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <small class="input-help" v-html="$t('admin_settings.others.mimetypes_blacklist_help')"></small>
                </div>

                 <div class="block-wrapper">
                    <label>{{ $t('admin_settings.others.upload_limit') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Upload Limit" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'upload_limit', app.uploadLimit)" v-model="app.uploadLimit" :placeholder="$t('admin_settings.others.upload_limit_plac')" type="number" min="0" step="1" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <small class="input-help" v-html="$t('admin_settings.others.upload_limit_help')"></small>
                </div>

                <FormLabel class="mt-70">
                    {{ $t('admin_settings.others.section_cache') }}
                </FormLabel>
                <InfoBox>
                    {{ $t('admin_settings.others.cache_disclaimer') }}
                </InfoBox>
                <ButtonBase @click.native="flushCache" :loading="isFlushingCache" :disabled="isFlushingCache" type="submit" button-style="theme" class="submit-button">
                    {{ $t('admin_settings.others.cache_clear') }}
                </ButtonBase>
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
    import {events} from '@/bus'
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
                isLoading: true,
                isFlushingCache: false,
                app: undefined,
            }
        },
        methods: {
            flushCache() {

                this.isFlushingCache = true

                axios.get('/api/flush-cache')
                    .then(() => {
                        events.$emit('toaster', {
                            type: 'success',
                            message: 'Your cache was successfully deleted.',
                        })
                    })
                    .finally(() => {
                        this.isFlushingCache = false
                    })
            }
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'contact_email|google_analytics|storage_default|registration|storage_limitation|mimetypes_blacklist|upload_limit'
                }
            })
                .then(response => {
                    this.isLoading = false

                    this.app = {
                        contactMail: response.data.contact_email,
                        googleAnalytics: response.data.google_analytics,
                        defaultStorage: response.data.storage_default,
                        userRegistration: parseInt(response.data.registration),
                        storageLimitation: parseInt(response.data.storage_limitation),
                        mimetypesBlacklist : response.data.mimetypes_blacklist,
                        uploadLimit: response.data.upload_limit
                    }
                })
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
</style>
