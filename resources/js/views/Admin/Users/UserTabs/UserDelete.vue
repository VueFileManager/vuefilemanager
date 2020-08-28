<template>
    <PageTab v-if="user" class="form-fixed-width">
        <PageTabGroup>
            <FormLabel>{{ $t('user_box_delete.title') }}</FormLabel>
            <InfoBox>
                <p>{{ $t('user_box_delete.description') }}</p>
            </InfoBox>
            <ValidationObserver ref="deleteUser" @submit.prevent="deleteUser" v-slot="{ invalid }" tag="form" class="form block-form">
                <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive" name="User name" :rules="'required|is:' + user.data.attributes.name">
                    <label>{{ $t('admin_page_user.label_delete_user', {user: user.data.attributes.name}) }}:</label>
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
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'

    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
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
            FormLabel,
            InfoBox,
            PageTabGroup,
            PageTab,
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
                    .post(this.$store.getters.api + '/users/' + this.$route.params.id + '/delete',
                        {
                            data: {
                                name: this.userName
                            },
                            _method: 'delete'
                        }
                    )
                    .then((response) => {

                        if (response.status === 202) {
                            events.$emit('alert:open', {
                                emoji: '☹️',
                                title: this.$t('popup_deleted_user_aborted.title'),
                                message: this.$t('popup_deleted_user_aborted.message'),
                            })
                        }

                        if (response.status === 204) {
                            events.$emit('success:open', {
                                emoji: '👍',
                                title: this.$t('popup_deleted_user.title'),
                                message: this.$t('popup_deleted_user.message'),
                            })

                            this.$router.push({name: 'Users'})
                        }
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
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
