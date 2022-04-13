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
            <FormLabel icon="wifi">
                {{ $t('broadcasting') }}
            </FormLabel>

            <ValidationProvider tag="div" mode="passive" name="Broadcast Driver" rules="required" v-slot="{ errors }">
                <AppInputText title="Broadcast Driver" :error="errors[0]">
                    <SelectInput
                        v-model="broadcast.driver"
                        :options="broadcastDrivers"
                        placeholder="Select your broadcast driver"
                        :isError="errors[0]"
                    />
                </AppInputText>
            </ValidationProvider>

            <div v-if="broadcast.driver === 'native'">
                <ValidationProvider tag="div" mode="passive" name="Host" rules="required" v-slot="{ errors }">
                    <AppInputText title="Hostname or IP" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="broadcast.host"
                            placeholder="Type your hostname or IP"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Port" rules="required" v-slot="{ errors }">
                    <AppInputText title="Port" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="broadcast.port"
                            placeholder="Type your port"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="broadcast.driver === 'pusher'">
                <ValidationProvider tag="div" mode="passive" name="App ID" rules="required" v-slot="{ errors }">
                    <AppInputText title="App ID" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="broadcast.id"
                            placeholder="Type your app id"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Key" rules="required" v-slot="{ errors }">
                    <AppInputText title="Key" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="broadcast.key"
                            placeholder="Paste your key"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                    <AppInputText title="Secret" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="broadcast.secret"
                            placeholder="Paste your secret"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Cluster" rules="required" v-slot="{ errors }">
                    <AppInputText title="Cluster" :error="errors[0]">
                        <SelectInput
                            v-model="broadcast.cluster"
                            :options="pusherClusters"
                            placeholder="Select your cluster"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <ButtonBase
                :loading="isSendingBroadcastForm"
                :disabled="isSendingBroadcastForm"
                type="submit"
                button-style="theme"
                class="w-full sm:w-auto"
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
            isSendingBroadcastForm: false,
            broadcast: {
                driver: undefined,
                id: undefined,
                key: undefined,
                secret: undefined,
                cluster: undefined,
                port: undefined,
                host: undefined,
            },
            broadcastDrivers: [
                {
                    label: 'Pusher',
                    value: 'pusher',
                },
                {
                    label: 'VueFileManager Broadcast Server',
                    value: 'native',
                },
                {
                    label: 'None',
                    value: 'none',
                },
            ],
            pusherClusters: [
                {
                    label: 'US East (N. Virginia)',
                    value: 'mt1',
                },
                {
                    label: 'Asia Pacific (Singapore)',
                    value: 'ap1',
                },
                {
                    label: 'Asia Pacific (Mumbai)',
                    value: 'ap2',
                },
                {
                    label: 'US East (Ohio)',
                    value: 'us2',
                },
                {
                    label: 'Asia Pacific (Tokyo)',
                    value: 'ap3',
                },
                {
                    label: 'US West (Oregon)',
                    value: 'us3',
                },
                {
                    label: 'Asia Pacific (Sydney)',
                    value: 'ap4',
                },
                {
                    label: 'EU (Ireland)',
                    value: 'eu',
                },
                {
                    label: 'South America (São Paulo)',
                    value: 'sa1',
                },
            ],
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
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })
                })
                .finally(() => {
                    this.isSendingBroadcastForm = false

                    this.broadcast = {
                        driver: undefined,
                        id: undefined,
                        key: undefined,
                        secret: undefined,
                        cluster: undefined,
                        host: undefined,
                        port: undefined,
                    }
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
