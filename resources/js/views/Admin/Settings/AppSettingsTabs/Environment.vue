<template>
    <PageTab :is-loading="isLoading">
        <!--Broadcasting setup-->
        <ValidationObserver
            @submit.prevent="broadcastSetupSubmit"
            ref="broadcastSetup"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <BroadcastSetup v-model="broadcast" />

            <ButtonBase
                :loading="isSendingBroadcastForm"
                :disabled="isSendingBroadcastForm"
                type="submit"
                button-style="theme"
				class="mt-6 w-full sm:mt-7 sm:w-auto"
            >
                {{ $t('save_broadcast_settings') }}
            </ButtonBase>
        </ValidationObserver>

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
import BroadcastSetup from "../../../../components/Forms/BroadcastSetup";
import Echo from "laravel-echo";

export default {
    name: 'AppEnvironment',
    components: {
		BroadcastSetup,
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
            isSendingBroadcastForm: false,
            broadcast: undefined,
        }
    },
    methods: {
        async broadcastSetupSubmit() {
            // Validate fields
            const isValid = await this.$refs.broadcastSetup.validate()

            if (!isValid) return

            // Start loading
            this.isSendingBroadcastForm = true

            axios
                .post('/api/admin/settings/broadcast', { ...this.broadcast })
                .then(() => {
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('broadcast_driver_updated'),
                    })

					let config = {
						'pusher': {
							broadcasting: 'pusher',
							broadcastingKey: this.broadcast.key,
							broadcastingHost: this.broadcast.host,
							broadcastingCluster: this.broadcast.cluster,
						},
						'native': {
							broadcasting: 'pusher',
							broadcastingHost: this.broadcast.host,
							broadcastingKey: 'local',
							broadcastingCluster: 'local',
						},
						'none': {
							broadcasting: 'null',
						}
					}

					// Set up echo
					if (['pusher', 'native'].includes(this.broadcast.driver)) {
						window.Echo = new Echo({
							broadcaster: 'pusher',
							cluster: this.broadcast.cluster || 'local',
							key: this.broadcast.key || 'local',
							wsHost: this.broadcast.host,
							forceTLS: false,
							enabledTransports: ['ws', 'wss'],
						});
					}

					Object
						.entries(config[this.broadcast.driver])
						.map(([key, value]) => {
							this.$store.commit('REPLACE_CONFIG_VALUE', {
								key: key,
								value: value,
							})

							if (key === 'broadcasting' && value === 'pusher') {
								this.$store.dispatch('runConnection')
							}
						})
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })
                })
                .finally(() => {
                    this.isSendingBroadcastForm = false

                    this.broadcast = undefined
                })
        },
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
