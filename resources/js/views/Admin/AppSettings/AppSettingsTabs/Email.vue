<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup>
            <ValidationObserver @submit.prevent="EmailSetupSubmit" ref="EmailSetup" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>{{ $t('admin_settings.email.section_email') }}</FormLabel>

                <InfoBox>
                    <p v-html="$t('admin_settings.email.email_disclaimer')"></p>
                </InfoBox>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.driver') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Driver" rules="required" v-slot="{ errors }">
                        <input v-model="mail.driver" :placeholder="$t('admin_settings.email.driver_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.host') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Host" rules="required" v-slot="{ errors }">
                        <input v-model="mail.host" :placeholder="$t('admin_settings.email.host_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.port') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Port" rules="required" v-slot="{ errors }">
                        <input v-model="mail.port" :placeholder="$t('admin_settings.email.port_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.username') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Username" rules="required" v-slot="{ errors }">
                        <input v-model="mail.username" :placeholder="$t('admin_settings.email.username_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.password') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Password" rules="required" v-slot="{ errors }">
                        <input v-model="mail.password" :placeholder="$t('admin_settings.email.password_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.email.encryption') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Encryption" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="mail.encryption" :options="encryptionList" :placeholder="$t('admin_settings.email.encryption_plac')" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit"
                            button-style="theme" class="submit-button">
                    {{ $t('admin_settings.email.save_button') }}
                </ButtonBase>
            </ValidationObserver>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '/resources/js/components/Others/StorageItemDetail'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'AppAppearance',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
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
                isSendingRequest: false,
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
            async EmailSetupSubmit() {

                // Validate fields
                const isValid = await this.$refs.EmailSetup.validate();

                if (!isValid) return;

                // Start loading
                this.isSendingRequest = true

                // Send request to get verify account
                axios
                    .post('/api/admin/settings/email', this.mail)
                    .then(() => {

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.email_set'),
                        })
                    })
                    .catch(() => {
                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
                    .finally(() => {

                        // End loading
                        this.isSendingRequest = false
                    })
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
    @import '/resources/sass/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }

    @media only screen and (max-width: 960px) {

    }

    .dark-mode {

    }

</style>
