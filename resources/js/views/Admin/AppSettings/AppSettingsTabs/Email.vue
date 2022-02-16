<template>
    <PageTab :is-loading="isLoading">
        <ValidationObserver
            @submit.prevent="EmailSetupSubmit"
            ref="EmailSetup"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <FormLabel>{{ $t('admin_settings.email.section_email') }}</FormLabel>

            <ValidationProvider tag="div" mode="passive" name="Mail Driver" rules="required" v-slot="{ errors }">
                <AppInputText title="Mail Driver" :error="errors[0]">
                    <SelectInput
                        v-model="mailDriver"
                        :default="mailDriver"
                        :options="mailDriverList"
                        placeholder="Select your mail driver"
                        :isError="errors[0]"
                    />
                </AppInputText>
            </ValidationProvider>

            <div v-if="mailDriver === 'smtp'">
                <ValidationProvider tag="div" mode="passive" name="Mail Host" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Host" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.host"
                            placeholder="Type your mail host"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Port" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Port" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.port"
                            placeholder="Type your mail port"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Username" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Username" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.username"
                            placeholder="Type your mail username"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Password" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Password" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.password"
                            placeholder="Type your mail password"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Mail Encryption"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Mail Encryption" :error="errors[0]">
                        <SelectInput
                            v-model="smtp.encryption"
                            :default="smtp.encryption"
                            :options="mailEncryptionList"
                            placeholder="Select your mail encryption"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'mailgun'">
                <ValidationProvider tag="div" mode="passive" name="Domain" rules="required" v-slot="{ errors }">
                    <AppInputText title="Domain" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.domain"
                            placeholder="Type your domain"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                    <AppInputText title="Secret" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.secret"
                            placeholder="Type your secret"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Endpoint" rules="required" v-slot="{ errors }">
                    <AppInputText title="Endpoint" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.endpoint"
                            placeholder="Type your endpoint"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'postmark'">
                <ValidationProvider tag="div" mode="passive" name="Token" rules="required" v-slot="{ errors }">
                    <AppInputText title="Token" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="postmark.token"
                            placeholder="Type your token"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'ses'">
                <ValidationProvider tag="div" mode="passive" name="Access Key" rules="required" v-slot="{ errors }">
                    <AppInputText title="Access Key" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.access_key"
                            placeholder="Type your access key"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Secret Access Key"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Secret Access Key" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.secret_access_key"
                            placeholder="Type your secret access key"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Default Region" rules="required" v-slot="{ errors }">
                    <AppInputText title="Default Region" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.default_region"
                            placeholder="Type your default region"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Session Token" v-slot="{ errors }">
                    <AppInputText title="Session Token" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.session_token"
                            placeholder="Type your session token"
                            type="text"
                            :class="{ 'border-red': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <ButtonBase
                :loading="isSendingRequest"
                :disabled="isSendingRequest"
                type="submit"
                button-style="theme"
                class="w-full sm:w-auto"
            >
                {{ $t('admin_settings.email.save_button') }}
            </ButtonBase>
        </ValidationObserver>
    </PageTab>
</template>

<script>
import AppInputText from '../../../../components/Admin/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Others/Layout/PageTabGroup'
import SelectInput from '../../../../components/Others/Forms/SelectInput'
import ImageInput from '../../../../components/Others/Forms/ImageInput'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import ButtonBase from '../../../../components/FilesView/ButtonBase'
import SetupBox from '../../../../components/Others/Forms/SetupBox'
import PageTab from '../../../../components/Others/Layout/PageTab'
import InfoBox from '../../../../components/Others/Forms/InfoBox'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../../../bus'
import axios from 'axios'
import { mapGetters } from 'vuex'

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
    computed: {
        ...mapGetters(['mailEncryptionList', 'mailDriverList']),
    },
    data() {
        return {
            isLoading: false,
            isSendingRequest: false,
            mailDriver: undefined,
            ses: {
                access_key: undefined,
                secret_access_key: undefined,
                default_region: undefined,
                session_token: undefined,
            },
            smtp: {
                host: undefined,
                port: undefined,
                username: undefined,
                password: undefined,
                encryption: undefined,
            },
            mailgun: {
                domain: undefined,
                secret: undefined,
                endpoint: undefined,
            },
            postmark: {
                token: undefined,
            },
        }
    },
    methods: {
        async EmailSetupSubmit() {
            // Validate fields
            const isValid = await this.$refs.EmailSetup.validate()

            if (!isValid) return

            // Start loading
            this.isSendingRequest = true

            // Send request to get verify account
            axios
                .post('/api/admin/settings/email', {
                    environment: this.environment,
                    storage: this.storage,
                    mailDriver: this.mailDriver,
                    smtp: this.smtp,
                    mailgun: this.mailgun,
                    ses: this.ses,
                    postmark: this.postmark,
                })
                .then(() => {
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.email_set'),
                    })
                })
                .catch((error) => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
                .finally(() => {
                    this.isSendingRequest = false
                })
        },
    },
}
</script>
