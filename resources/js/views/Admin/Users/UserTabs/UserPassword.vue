<template>
    <PageTab>
		<div class="card shadow-card">
            <FormLabel>
                {{ $t('user_box_password.title') }}
            </FormLabel>
            <InfoBox>
                <p>{{ $t('user_box_password.description') }}</p>
            </InfoBox>
            <ButtonBase @click.native="requestPasswordResetEmail" :loading="isSendingRequest"
                        :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
                {{ $t('admin_page_user.send_password_link') }}
            </ButtonBase>
		</div>
    </PageTab>
</template>

<script>
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'UserPassword',
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
                isLoading: false,
                isSendingRequest: false,
            }
        },
        methods: {
            requestPasswordResetEmail() {

                this.isSendingRequest = true

                axios
                    .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/reset-password',
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
    @import '/resources/sass/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
