<template>
    <div class="page-tab">

        <!--Change role-->
        <div class="page-tab-group">
            <SetupBox
                    theme="base"
                    :title="$t('user_box_role.title')"
                    :description="$t('user_box_role.description')"
            >
                <ValidationObserver ref="changeRole" @submit.prevent="changeRole" v-slot="{ invalid }" tag="form" class="form block-form">
                    <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive" name="Role" rules="required">
                        <label>{{ $t('admin_page_user.select_role') }}:</label>
                        <div class="single-line-form">
                            <SelectInput v-model="userRole" :options="roles" :placeholder="$t('admin_page_user.select_role')" :isError="errors[0]"/>
                            <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
                                {{ $t('admin_page_user.save_role') }}
                            </ButtonBase>
                        </div>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </ValidationObserver>
            </SetupBox>
        </div>

        <!--Personal Information-->
        <div class="page-tab-group">
            <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form">

                <b class="form-group-label">{{ $t('admin_page_user.label_person_info') }}</b>
                <div class="wrapper-inline">

                    <!--Email-->
                    <div class="block-wrapper">
                        <label>{{ $t('page_registration.label_email') }}</label>
                        <div class="input-wrapper">
                            <input :value="user.attributes.email" :placeholder="$t('page_registration.placeholder_email')" type="email" disabled />
                        </div>
                    </div>

                    <!--Name-->
                    <div class="block-wrapper">
                        <label>{{ $t('page_registration.label_name') }}</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required"
                                            v-slot="{ errors }">
                            <input :value="user.attributes.name"
                                   :placeholder="$t('page_registration.placeholder_name')"
                                   type="text"
                                   disabled
                            />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserDetail',
        props: [
            'user'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        computed: {
            ...mapGetters(['roles']),
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
                    .patch(this.$store.getters.api + '/users/' + this.$route.params.id + '/role', {
                        attributes: {
                            role: this.userRole,
                        }
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

    .page-tab {

        .page-tab-group {
            margin-bottom: 45px;
        }
    }

    .block-form {
        max-width: 100%;
    }


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
