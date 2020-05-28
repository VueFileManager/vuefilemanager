<template>
    <div class="page-tab">

        <!--Change role-->
        <div class="page-tab-group">
            <SetupBox
                    theme="danger"
                    :title="$t('user_box_delete.title')"
                    :description="$t('user_box_delete.description')"
            >
                <ValidationObserver ref="deleteUser" @submit.prevent="deleteUser" v-slot="{ invalid }" tag="form"
                                    class="form block-form">
                    <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive"
                                        name="User name" :rules="'required|is:' + user.attributes.name">
                        <label>{{ $t('admin_page_user.label_delete_user', {user: user.attributes.name}) }}:</label>
                        <div class="single-line-form">
                            <input v-model="userName"
                                   :placeholder="$t('admin_page_user.placeholder_delete_user')"
                                   type="text"
                                   :class="{'is-error': errors[0]}"
                            />
                            <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit"
                                        button-style="danger" class="submit-button">
                                {{ $t('admin_page_user.delete_user') }}
                            </ButtonBase>
                        </div>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </ValidationObserver>
            </SetupBox>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required, is} from 'vee-validate/dist/rules'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserDelete',
        props: [
            'user'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isSendingRequest: false,
                isLoading: false,
                userName: '',
            }
        },
        methods: {
            async deleteUser() {

                // Validate fields
                const isValid = await this.$refs.deleteUser.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                axios
                    .delete(this.$store.getters.api + '/users/' + this.$route.params.id + '/delete',
                        {
                            data: {
                                name: this.userName
                            }
                        }
                    )
                    .then(() => {
                        this.isSendingRequest = false

                        // Show error message
                        events.$emit('success:open', {
                            emoji: '👍',
                            title: this.$t('popup_deleted_user.title'),
                            message: this.$t('popup_deleted_user.message'),
                        })

                        this.$router.push({name: 'Users'})
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
