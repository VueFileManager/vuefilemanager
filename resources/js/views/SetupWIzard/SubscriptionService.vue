<template>
    <AuthContentWrapper ref="auth">

        <!--Licence Verify-->
        <AuthContent name="subscription-service" :visible="true">

            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>You can charge users for storage space by monthly billing plans. Please, select your charging service or skip this step if you don't want charge users:</h2>
            </div>

            <div class="services">
                <router-link :to="{name: 'StripeCredentials'}" tag="div" class="service-card">
                    <img src="/assets/icons/stripe-service.svg" alt="Stripe" class="service-logo">

                    <div class="service-content">
                        <b class="service-title">Charging with Stripe</b>
                        <p class="service-description">You can create custom storage plans and charge your users with monthly subscription.</p>
                    </div>

                    <router-link :to="{name: 'StripeCredentials'}" class="service-link">
                        <span>Set Up Billing and Plans With Stripe</span>
                        <chevron-right-icon size="22" class="icon"></chevron-right-icon>
                    </router-link>
                </router-link>
            </div>

            <p class="additional-link">
                <router-link :to="{name: 'EnvironmentSetup'}">
                    <AuthButton  class="skip-subscription-setup" icon="chevron-right" text="I will set up Stripe later" />
                </router-link>
            </p>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import { SettingsIcon, ChevronRightIcon } from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'SubscriptionService',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            ChevronRightIcon,
            SettingsIcon,
            AuthContent,
            AuthButton,
            required,
        },
        data() {
            return {
                isLoading: false,
            }
        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';

    .services {
        margin: 0 auto;
    }

    .service-card {
        text-align: left;
        box-shadow: 0 5px 30px 5px rgba(#3D4EFD, 0.25);
        border-radius: 20px;
        max-width: 415px;
        display: inline-block;
        padding: 30px;
        background: rgb(58,75,255);
        background: linear-gradient(135deg, rgba(58,75,255,1) 0%, rgba(103,114,229,1) 100%);
        @include transition(200ms);

        &:hover {
            cursor: pointer;
            box-shadow: 0 8px 35px 5px rgba(#3D4EFD, 0.4);
            @include transform(scale(1.02));
        }

        .service-logo {
            margin-bottom: 30px;
            display: block;
        }

        .service-content {
            margin-bottom: 65px;

            .service-title {
                @include font-size(18);
                font-weight: 700;
                color: white;
                margin-bottom: 5px;
                display: block;
            }

            .service-description {
                @include font-size(16);
                font-weight: 600;
                color: white;
                opacity: 0.8;
            }
        }

        .service-link {
            display: flex;
            align-items: center;

            .icon {
                margin-left: 5px;

                polyline {
                    stroke: white
                }
            }

            span {
                @include font-size(16);
                font-weight: 700;
                color: white;
            }
        }
    }

    .skip-subscription-setup {
        border: none !important;
    }

    .auth-form input {
        min-width: 380px;
    }
</style>
