<template>
    <div v-if="user" class="card shadow-card">
        <FormLabel>
            {{ $t('user_box_delete.title') }}
        </FormLabel>
        <ValidationObserver ref="deleteUser" @submit.prevent="deleteUser" v-slot="{ invalid }" tag="form">
            <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="User name" rules="required">
                <AppInputText
                    :title="
                        $t('admin_page_user.label_delete_user', {
                            user: user.data.relationships.settings.data.attributes.name.trim(),
                        })
                    "
                    :description="$t('user_box_delete.description')"
                    :error="errors[0]"
                    :is-last="true"
                >
                    <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                        <input
                            v-model="userName"
                            :placeholder="$t('admin_page_user.placeholder_delete_user')"
                            type="text"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <ButtonBase
                            :loading="isSendingRequest"
                            :disabled="isSendingRequest"
                            type="submit"
                            button-style="danger"
                            class="w-full sm:w-auto"
                        >
                            {{ $t('admin_page_user.delete_user') }}
                        </ButtonBase>
                    </div>
                </AppInputText>
            </ValidationProvider>
        </ValidationObserver>
    </div>
</template>

<script>
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import InfoBox from '../../../../components/UI/Others/InfoBox'

import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import PageTab from '../../../../components/Layout/PageTab'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import { required, is } from 'vee-validate/dist/rules'
import { events } from '../../../../bus'
import axios from 'axios'

export default {
    name: 'UserDelete',
    props: ['user'],
    components: {
        AppInputText,
        FormLabel,
        InfoBox,
        PageTabGroup,
        PageTab,
        ValidationProvider,
        ValidationObserver,
        ButtonBase,
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
            const isValid = await this.$refs.deleteUser.validate()

            if (!isValid) return

            if (this.userName.trim() !== this.user.data.relationships.settings.data.attributes.name.trim()) {
                this.$refs.deleteUser.setErrors({
                    'User name': 'The user name is not the same.',
                })

                return
            }

            this.isSendingRequest = true

            axios
                .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id, {
                    name: this.userName,
                    _method: 'delete',
                })
                .then((response) => {
                    if (response.status === 202) {
                        events.$emit('alert:open', {
                            emoji: '☹️',
                            title: this.$t('popup_deleted_user_aborted.title'),
                            message: this.$t('popup_deleted_user_aborted.message'),
                        })
                    }

                    if (response.status === 200) {
                        events.$emit('success:open', {
                            emoji: '👍',
                            title: this.$t('popup_deleted_user.title'),
                            message: this.$t('popup_deleted_user.message'),
                        })

                        this.$router.push({ name: 'Users' })
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
        },
    },
}
</script>
