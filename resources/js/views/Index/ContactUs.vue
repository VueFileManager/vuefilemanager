<template>
    <div class="landing-page">

        <!--Navigation-->
        <Navigation class="page-wrapper small"/>

        <!--Page content-->
        <div class="page-wrapper small">

            <!--Headline-->
            <PageTitle
                    class="headline"
                    title="Contact Us"
                    description="Dominion, open bearing brought may dominion male beginning."
            ></PageTitle>

            <!--Content-->
            <div class="page-content">
                <ValidationObserver @submit.prevent="contactForm" ref="contactForm" v-slot="{ invalid }" tag="form"
                                    class="form block-form">

                    <div class="block-wrapper">
                        <label>{{ $t('page_registration.label_email') }}</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                            v-slot="{ errors }">
                            <input v-model="contact.email" :placeholder="$t('page_registration.placeholder_email')" type="email"
                                   :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="block-wrapper">
                        <label>Message:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Message" rules="required"
                                            v-slot="{ errors }">
                            <textarea v-model="contact.message" placeholder="Type your message here..." rows="4" :class="{'is-error': errors[0]}"></textarea>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div>
                        <AuthButton class="submit-button" icon="chevron-right" text="Send Message" :loading="isLoading" :disabled="isLoading"/>
                    </div>
                </ValidationObserver>
            </div>
        </div>

        <!--Footer-->
        <PageFooter/>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthButton from '@/components/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'

    import PageTitle from '@/components/Index/Components/PageTitle'
    import PageFooter from '@/components/Index/IndexPageFooter'
    import Navigation from '@/components/Index/IndexNavigation'
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
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
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
                    .post('/api/user/register', this.register)
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.$router.push({name: 'Files'})
                    })
                    .catch(error => {

                        if (error.response.status == 401) {

                            if (error.response.data.error === 'invalid_client') {
                                events.$emit('alert:open', {
                                    emoji: '🤔',
                                    title: this.$t('popup_passport_error.title'),
                                    message: this.$t('popup_passport_error.message')
                                })
                            }
                        }

                        if (error.response.status == 500) {

                            events.$emit('alert:open', {
                                emoji: '🤔',
                                title: this.$t('popup_signup_error.title'),
                                message: this.$t('popup_signup_error.message')
                            })
                        }

                        if (error.response.status == 422) {

                            if (error.response.data.errors['email']) {

                                this.$refs.sign_up.setErrors({
                                    'E-Mail': error.response.data.errors['email']
                                });
                            }

                            if (error.response.data.errors['password']) {

                                this.$refs.sign_up.setErrors({
                                    'Your New Password': error.response.data.errors['password']
                                });
                            }
                        }

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

    .headline {
        padding-top: 70px;
        padding-bottom: 50px;
    }

    .page-content {

        p {
            @include font-size(20);
            font-weight: 500;
            line-height: 1.65;
            padding-bottom: 30px;
        }
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

    @media only screen and (max-width: 690px) {

    }
</style>
