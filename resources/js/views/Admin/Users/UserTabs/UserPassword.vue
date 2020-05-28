<template>
    <div class="page-tab">

        <!--Change role-->
        <div class="page-tab-group">
            <SetupBox
                    theme="base"
                    :title="$t('user_box_password.title')"
                    :description="$t('user_box_password.description')"
            >
                <ButtonBase @click.native="requestPasswordResetEmail" :loading="isSendingRequest"
                            :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
                    {{ $t('admin_page_user.send_password_link') }}
                </ButtonBase>
            </SetupBox>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserPassword',
        components: {
            ValidationProvider,
            ValidationObserver,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
            }
        },
        methods: {
            requestPasswordResetEmail() {

                this.isSendingRequest = true

                axios
                    .post(this.$store.getters.api + '/users/' + this.$route.params.id + '/send-password-email',
                        {}
                    )
                    .then(() => {
                        this.isSendingRequest = false

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.sended_password'),
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
