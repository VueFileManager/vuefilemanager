<template>
    <div id="single-page">
        <div id="page-content" class="medium-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'PlanSettings', params: {id: plan.id}}"
                                 class="menu-list-item link">
                        <div class="icon">
                            <settings-icon size="17"></settings-icon>
                        </div>
                        <div class="label">
                            Settings
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanTransactions', params: {id: plan.id}}"
                                 class="menu-list-item link">
                        <div class="icon">
                            <credit-card-icon size="17"></credit-card-icon>
                        </div>
                        <div class="label">
                            Transactions
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanDelete', params: {id: plan.id}}"
                                 class="menu-list-item link">
                        <div class="icon">
                            <trash2-icon size="17"></trash2-icon>
                        </div>
                        <div class="label">
                            Delete Plan
                        </div>
                    </router-link>
                </div>

                <!--Router Content-->
                <router-view :plan="plan"/>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {CreditCardIcon, SettingsIcon, Trash2Icon} from 'vue-feather-icons'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Gateway',
        components: {
            Trash2Icon,
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
                plan: undefined,
            }
        },
        created() {

            this.plan = {
                id: '1',
                type: 'plans',
                attributes: {
                    name: 'Starter Plan',
                    description: 'This plan fits for every storage starter.',
                    status: 1,
                    price: 9.99,
                    capacity: 200,
                    subscribers: 172,
                }
            }

            /*axios.get('/api/gateway/' + this.$route.params.name)
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
