<template>
    <div id="single-page">
        <div id="page-content" class="medium-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <!--User thumbnail-->
                <div class="user-thumbnail">
                    <div class="avatar">
                        <img :src="gateway.attributes.avatar" :alt="gateway.attributes.gateway">
                    </div>
                    <div class="info">
                        <b class="name">{{ gateway.attributes.gateway }}</b>
                        <span class="email">Payment Gateway</span>
                    </div>
                </div>

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'GatewaySettings', params: {name: gateway.attributes.type}}" class="menu-list-item link">
                        <div class="icon">
                            <settings-icon size="17"></settings-icon>
                        </div>
                        <div class="label">
                            Settings
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'GatewayTransactions', params: {name: gateway.attributes.type}}" class="menu-list-item link">
                        <div class="icon">
                            <credit-card-icon size="17"></credit-card-icon>
                        </div>
                        <div class="label">
                            Transactions
                        </div>
                    </router-link>
                </div>

                <!--Router Content-->
                <router-view :gateway="gateway"/>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {CreditCardIcon, SettingsIcon} from 'vue-feather-icons'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            SettingsIcon,
            CreditCardIcon,
            SectionTitle,
            MobileHeader,
            PageHeader,
            Spinner,
        },
        data() {
            return {
                isLoading: false,
                gateway: {
                    id: '1',
                    type: 'payment_method',
                    attributes: {
                        type: 'stripe',
                        gateway: 'Stripe',
                        avatar: '/assets/images/stripe-logo-thumbnail.png',
                        status: 1,
                        payments_processed: 798,
                        active_subscribers: 3587,
                    }
                },
            }
        },
        created() {
            /*axios.get('/api/users/' + this.$route.params.id + '/detail')
                .then(response => {
                    this.user = response.data.data
                    this.isLoading = false
                })*/
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .user-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .avatar {
            margin-right: 20px;

            img {
                line-height: 0;
                width: 62px;
                height: 62px;
                border-radius: 12px;
            }
        }

        .info {

            .name {
                display: block;
                @include font-size(17);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(14);
            }
        }
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {
        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

</style>
