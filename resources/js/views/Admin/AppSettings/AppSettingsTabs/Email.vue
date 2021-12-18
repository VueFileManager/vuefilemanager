<template>
    <PageTab :is-loading="isLoading">
        <ValidationObserver @submit.prevent="EmailSetupSubmit" ref="EmailSetup" v-slot="{ invalid }" tag="form" class="card shadow-card">
            <FormLabel>{{ $t('admin_settings.email.section_email') }}</FormLabel>
            <InfoBox>
                <p v-html="$t('admin_settings.email.email_disclaimer')"></p>
            </InfoBox>
            <ValidationProvider tag="div" mode="passive" name="Mail Driver" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.driver')" :error="errors[0]">
                    <input v-model="mail.driver" :placeholder="$t('admin_settings.email.driver_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
                </AppInputText>
            </ValidationProvider>
            <ValidationProvider tag="div" mode="passive" name="Mail Host" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.host')" :error="errors[0]">
                    <input v-model="mail.host" :placeholder="$t('admin_settings.email.host_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
                </AppInputText>
            </ValidationProvider>
            <ValidationProvider tag="div" mode="passive" name="Mail Port" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.port')" :error="errors[0]">
                    <input v-model="mail.port" :placeholder="$t('admin_settings.email.port_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
                </AppInputText>
            </ValidationProvider>
            <ValidationProvider tag="div" mode="passive" name="Mail Username" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.username')" :error="errors[0]">
                    <input v-model="mail.username" :placeholder="$t('admin_settings.email.username_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
                </AppInputText>
            </ValidationProvider>
            <ValidationProvider tag="div" mode="passive" name="Mail Password" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.password')" :error="errors[0]">
                    <input v-model="mail.password" :placeholder="$t('admin_settings.email.password_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
                </AppInputText>
            </ValidationProvider>
            <ValidationProvider tag="div" mode="passive" name="Mail Encryption" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_settings.email.encryption')" :error="errors[0]">
                    <SelectInput v-model="mail.encryption" :options="encryptionList" :placeholder="$t('admin_settings.email.encryption_plac')" :isError="errors[0]"/>
                </AppInputText>
            </ValidationProvider>
            <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
                {{ $t('admin_settings.email.save_button') }}
            </ButtonBase>
        </ValidationObserver>
    </PageTab>
</template>

<script>
	import AppInputText from "../../../../components/Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
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
			AppInputText,
            ValidationObserver,
            ValidationProvider,
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
