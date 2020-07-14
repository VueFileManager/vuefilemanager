<template>
    <div class="landing-page">

        <!--Navigation-->
        <Navigation class="page-wrapper small"/>

        <!--Page content-->
        <div class="page-wrapper small">

            <!--Headline-->
            <PageTitle
                    class="headline"
                    :title="$t('page_contact_us.title')"
                    :description="$t('page_contact_us.description')"
            ></PageTitle>

            <ValidationObserver v-if="! isSuccess" @submit.prevent="contactForm" ref="contactForm" v-slot="{ invalid }" tag="form"
                                class="form block-form">

                <div class="block-wrapper">
                    <label>{{ $t('page_contact_us.form.email') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="contact.email" :placeholder="$t('page_contact_us.form.email_plac')" type="email"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_contact_us.form.message') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Message" rules="required"
                                        v-slot="{ errors }">
                        <textarea v-model="contact.message" :placeholder="$t('page_contact_us.form.message_plac')" rows="6" :class="{'is-error': errors[0]}"></textarea>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <InfoBox v-if="isError">
                    <p>{{ $t('page_contact_us.error_message') }}</p>
                </InfoBox>

                <div>
                    <AuthButton class="submit-button" icon="chevron-right" :text="$t('page_contact_us.form.submit_button')" :loading="isLoading" :disabled="isLoading"/>
                </div>
            </ValidationObserver>
            <InfoBox v-if="isSuccess">
                <p>{{ $t('page_contact_us.success_message') }}</p>
            </InfoBox>
        </div>

        <!--Footer-->
        <PageFooter/>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PageTitle from '@/components/Index/Components/PageTitle'
    import PageFooter from '@/components/Index/IndexPageFooter'
    import Navigation from '@/components/Index/IndexNavigation'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import AuthButton from '@/components/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'ContactUs',
        components: {
            ValidationProvider,
            ValidationObserver,
            AuthButton,
            PageFooter,
            Navigation,
            PageTitle,
            required,
            InfoBox,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
                isSuccess: false,
                isError: false,
                contact: {
                    email: '',
                    message: '',
                },
            }
        },
        methods: {
            async contactForm() {

                // Validate fields
                const isValid = await this.$refs.contactForm.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post('/api/contact', this.contact)
                    .then(() => {
                        this.isSuccess = true
                    })
                    .catch(() => {
                        this.isError = true
                    })
                    .finally(() => {
                        // End loading
                        this.isLoading = false
                    })
            }
        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .form {
        max-width: 100%;
    }

    .headline {
        padding-top: 70px;
        padding-bottom: 50px;
    }

    .form.block-form {

        .submit-button {
            margin-top: 20px;
            margin-left: 0;
            margin-right: 0;
        }
    }

    @media (prefers-color-scheme: dark) {

    }

    @media only screen and (max-width: 960px) {
        .headline {
            padding-top: 50px;
            padding-bottom: 30px;
        }
    }
</style>
