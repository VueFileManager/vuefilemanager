<template>
    <PageTab>
        <!--Change role-->
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('user_box_role.title') }}
            </FormLabel>
            <ValidationObserver ref="changeRole" @submit.prevent="changeRole" v-slot="{ invalid }" tag="form">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Role" rules="required">
                    <AppInputText
                        :title="$t('admin_page_user.select_role')"
                        :description="$t('user_box_role.description')"
                        :error="errors[0]"
                        :is-last="true"
                    >
                        <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                            <SelectInput
                                v-model="userRole"
                                :options="$translateSelectOptions(roles)"
                                :placeholder="$t('admin_page_user.select_role')"
                                :isError="errors[0]"
                            />
                            <ButtonBase
                                :loading="isSendingRequest"
                                :disabled="isSendingRequest"
                                type="submit"
                                button-style="theme"
                                class="w-full sm:w-auto"
                            >
                                {{ $t('admin_page_user.save_role') }}
                            </ButtonBase>
                        </div>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('admin_page_user.label_person_info') }}
            </FormLabel>

            <!--Name-->
            <div class="justify-items md:flex md:space-x-4">
                <AppInputText :title="$t('first_name')" class="w-full">
                    <input
                        disabled
                        :value="user.data.relationships.settings.data.attributes.first_name"
                        :placeholder="$t('page_registration.placeholder_name')"
                        type="text"
                        class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    />
                </AppInputText>
                <AppInputText :title="$t('last_name')" class="w-full">
                    <input
                        disabled
                        :value="user.data.relationships.settings.data.attributes.last_name"
                        :placeholder="$t('page_registration.placeholder_name')"
                        type="text"
                        class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    />
                </AppInputText>
            </div>

            <AppInputText :title="$t('full_name')" :is-last="true">
                <input
                    :value="user.data.relationships.settings.data.attributes.name"
                    :placeholder="$t('page_registration.placeholder_name')"
                    type="text"
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    disabled
                />
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>{{ $t('billing_information') }}</FormLabel>
            <AppInputText :title="$t('name')">
                <input
                    :value="user.data.relationships.settings.data.attributes.name"
                    type="text"
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    disabled
                />
            </AppInputText>
            <AppInputText :title="$t('address')">
                <input
                    :value="user.data.relationships.settings.data.attributes.address"
                    type="text"
                    disabled
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                />
            </AppInputText>
            <AppInputText :title="$t('country')">
                <input
                    :value="user.data.relationships.settings.data.attributes.country"
                    type="text"
                    disabled
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                />
            </AppInputText>
            <div class="flex space-x-4">
                <AppInputText :title="$t('city')" class="w-full">
                    <input
                        :value="user.data.relationships.settings.data.attributes.city"
                        type="text"
                        disabled
                        class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    />
                </AppInputText>
                <AppInputText :title="$t('postal_code')" class="w-full">
                    <input
                        :value="user.data.relationships.settings.data.attributes.postal_code"
                        type="text"
                        disabled
                        class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                    />
                </AppInputText>
            </div>
            <AppInputText :title="$t('state')">
                <input
                    :value="user.data.relationships.settings.data.attributes.state"
                    type="text"
                    disabled
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                />
            </AppInputText>
            <AppInputText :title="$t('phone_number')" :is-last="true">
                <input
                    :value="user.data.relationships.settings.data.attributes.phone_number"
                    type="text"
                    disabled
                    class="focus-border-theme input-dark disabled:text-gray-900 disabled:opacity-100"
                />
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import PageTab from '../../../../components/Layout/PageTab'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SelectInput from '../../../../components/Inputs/SelectInput'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
import { events } from '../../../../bus'
import axios from 'axios'

export default {
    name: 'UserDetail',
    props: ['user'],
    components: {
        AppInputText,
        PageTabGroup,
        PageTab,
        InfoBox,
        FormLabel,
        ValidationProvider,
        ValidationObserver,
        SelectInput,
        ButtonBase,
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
            const isValid = await this.$refs.changeRole.validate()

            if (!isValid) return

            this.isSendingRequest = true

            // Send request to get user reset link
            axios
                .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/role', {
                    attributes: {
                        role: this.userRole,
                    },
                    _method: 'patch',
                })
                .then(() => {
                    // Reset errors
                    this.$refs.changeRole.reset()

                    this.$emit('reload-user')

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.changed_user'),
                    })
                })
                .catch(() => {
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
