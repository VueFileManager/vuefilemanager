<template>
    <PageTab :is-loading="isLoading">
        <!--Storage setup-->
        <ValidationObserver
            @submit.prevent="storageSetupSubmit"
            ref="storageSetup"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <StorageSetup v-model="storage" />

            <ButtonBase
                :loading="isSendingStorageForm"
                :disabled="isSendingStorageForm"
                type="submit"
                button-style="theme"
                class="mt-6 w-full sm:mt-7 sm:w-auto"
            >
                {{ $t('save_storage_settings') }}
            </ButtonBase>
        </ValidationObserver>

        <!--Mail setup-->
        <ValidationObserver
            @submit.prevent="emailSetupSubmit"
            ref="EmailSetup"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <MailSetup v-model="mail" />

            <ButtonBase
                :loading="isSendingEmailForm"
                :disabled="isSendingEmailForm"
                type="submit"
                button-style="theme"
                class="mt-6 w-full sm:mt-7 sm:w-auto"
            >
                {{ $t('admin_settings.email.save_button') }}
            </ButtonBase>
        </ValidationObserver>
    </PageTab>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full'
import SelectInput from '../../../../components/Inputs/SelectInput'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import StorageSetup from '../../../../components/Forms/StorageSetup'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import PageTab from '../../../../components/Layout/PageTab'
import MailSetup from '../../../../components/Forms/MailSetup'
import { events } from '../../../../bus'
import axios from 'axios'

export default {
    name: 'AppEnvironment',
    components: {
        ValidationObserver,
        ValidationProvider,
        StorageSetup,
        AppInputText,
        SelectInput,
        ButtonBase,
        MailSetup,
        FormLabel,
        PageTab,
    },
    data() {
        return {
            storage: undefined,
            mail: undefined,
            isLoading: false,
            isSendingEmailForm: false,
            isSendingStorageForm: false,
        }
    },
    methods: {
        async storageSetupSubmit() {
            // Validate fields
            const isValid = await this.$refs.storageSetup.validate()

            if (!isValid) return

            // Start loading
            this.isSendingStorageForm = true

            axios
                .post('/api/admin/settings/storage', {
                    storage: this.storage,
                })
                .then(() => {
                    this.storage = undefined

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('storage_driver_updated'),
                    })
                })
                .catch((error) => {
                    if ([401, 500].includes(error.response.status)) {
                        events.$emit('alert:open', {
                            title: error.response.data.title || this.$t('popup_error.title'),
                            message: error.response.data.message,
                        })
                    } else {
                        events.$emit('toaster', {
                            type: 'danger',
                            message: this.$t('popup_error.title'),
                        })
                    }
                })
                .finally(() => {
                    this.isSendingStorageForm = false
                })
        },
        async emailSetupSubmit() {
            // Validate fields
            const isValid = await this.$refs.EmailSetup.validate()

            if (!isValid) return

            // Start loading
            this.isSendingEmailForm = true

            axios
                .post('/api/admin/settings/email', {
                    mailDriver: this.mail.driver,
                    postmark: this.mail.postmark,
                    mailgun: this.mail.mailgun,
                    smtp: this.mail.smtp,
                    ses: this.mail.ses,
                })
                .then(() => {
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.email_set'),
                    })
                })
                .catch((error) => {
                    if (error.response.status === 401 && error.response.data.type === 'mailer-connection-error') {
                        events.$emit('alert:open', {
                            title: 'Mailer Connection Error - Wrong Credentials',
                            message: error.response.data.message,
                        })
                    } else {
                        events.$emit('toaster', {
                            type: 'danger',
                            message: this.$t('popup_error.title'),
                        })
                    }
                })
                .finally(() => {
                    this.isSendingEmailForm = false
                })
        },
    },
}
</script>
