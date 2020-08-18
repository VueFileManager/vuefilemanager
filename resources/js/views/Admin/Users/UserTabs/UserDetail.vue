<template>
    <PageTab class="form-fixed-width">

        <!--Change role-->
        <PageTabGroup>
            <FormLabel>
                {{ $t('user_box_role.title') }}
            </FormLabel>

            <InfoBox>
                <p>{{ $t('user_box_role.description') }}</p>
            </InfoBox>

            <ValidationObserver ref="changeRole" @submit.prevent="changeRole" v-slot="{ invalid }" tag="form" class="form block-form">
                <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive" name="Role" rules="required">
                    <label>{{ $t('admin_page_user.select_role') }}:</label>
                    <div class="single-line-form">
                        <SelectInput v-model="userRole" :options="roles"
                                     :placeholder="$t('admin_page_user.select_role')" :isError="errors[0]"/>
                        <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit"
                                    button-style="theme" class="submit-button">
                            {{ $t('admin_page_user.save_role') }}
                        </ButtonBase>
                    </div>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>
        </PageTabGroup>

        <!--Personal Information-->
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>{{ $t('admin_page_user.label_person_info') }}</FormLabel>

                <!--Email-->
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_email') }}</label>
                    <div class="input-wrapper">
                        <input :value="user.data.attributes.email"
                               :placeholder="$t('page_registration.placeholder_email')"
                               type="email"
                               disabled
                        />
                    </div>
                </div>

                <!--Name-->
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_name') }}</label>
                    <div class="input-wrapper">
                        <input :value="user.data.attributes.name"
                               :placeholder="$t('page_registration.placeholder_name')"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
            </div>
        </PageTabGroup>

        <!--Billing Information-->
        <PageTabGroup v-if="config.isSaaS">
            <div class="form block-form">
                <FormLabel>{{ $t('user_settings.title_billing') }}</FormLabel>

                <div class="block-wrapper">
                    <label>{{ $t('user_settings.name') }}:</label>
                    <div class="input-wrapper">
                        <input :value="user.relationships.settings.data.attributes.billing_name"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.address') }}:</label>
                    <div class="input-wrapper">
                        <input :value="user.relationships.settings.data.attributes.billing_address"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.country') }}:</label>
                    <div class="input-wrapper">
                        <input :value="user.relationships.settings.data.attributes.billing_country"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
                <div class="wrapper-inline">
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.city') }}:</label>
                        <div class="input-wrapper">
                            <input :value="user.relationships.settings.data.attributes.billing_city"
                                   type="text"
                                   disabled
                            />
                        </div>
                    </div>
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.postal_code') }}:</label>
                        <div class="input-wrapper">
                            <input :value="user.relationships.settings.data.attributes.billing_postal_code"
                                   type="text"
                                   disabled
                            />
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.state') }}:</label>
                    <div class="input-wrapper">
                        <input :value="user.relationships.settings.data.attributes.billing_state"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.phone_number') }}:</label>
                    <div class="input-wrapper">
                        <input :value="user.relationships.settings.data.attributes.billing_phone_number"
                               type="text"
                               disabled
                        />
                    </div>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserDetail',
        props: [
            'user'
        ],
        components: {
            PageTabGroup,
            PageTab,
            InfoBox,
            FormLabel,
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        computed: {
            ...mapGetters(['roles', 'config']),
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
                userRole: undefined,
            }
        },
        methods: {
            async changeRole() {

                // Validate fields
                const isValid = await this.$refs.changeRole.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/users/' + this.$route.params.id + '/role', {
                        attributes: {
                            role: this.userRole,
                        },
                        _method: 'patch'
                    })
                    .then(() => {

                        // Reset errors
                        this.$refs.changeRole.reset()

                        this.isSendingRequest = false

                        this.$emit('reload-user')

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.changed_user'),
                        })
                    })
                    .catch(() => {

                        this.isSendingRequest = false

                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
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
