<template>
    <PageTab class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>Email Setup</FormLabel>

                <div class="block-wrapper">
                    <label>Mail Driver:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Driver" rules="required" v-slot="{ errors }">
                        <input v-model="mail.driver" placeholder="Type your mail driver" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Host:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Host" rules="required" v-slot="{ errors }">
                        <input v-model="mail.host" placeholder="Type your mail host" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Port:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Port" rules="required" v-slot="{ errors }">
                        <input v-model="mail.port" placeholder="Type your mail port" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Username:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Username" rules="required" v-slot="{ errors }">
                        <input v-model="mail.username" placeholder="Type your mail username" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Password" rules="required" v-slot="{ errors }">
                        <input v-model="mail.password" placeholder="Type your mail password" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Mail Encryption:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Mail Encryption" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="mail.encryption" :options="encryptionList" placeholder="Select your mail encryption" :isError="errors[0]"/>
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
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
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
