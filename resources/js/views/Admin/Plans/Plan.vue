<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
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
                            {{ $t('admin_page_plans.tabs.settings') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanSubscribers', params: {id: plan.id}}"
                                 class="menu-list-item link">
                        <div class="icon">
                            <users-icon size="17"></users-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_plans.tabs.subscribers') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanDelete', params: {id: plan.id}}"
                                 class="menu-list-item link">
                        <div class="icon">
                            <trash2-icon size="17"></trash2-icon>
                        </div>
                        <div class="label">
                            {{ $t('admin_page_plans.tabs.delete') }}
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
    import {UsersIcon, SettingsIcon, Trash2Icon} from 'vue-feather-icons'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Plan',
        components: {
            UsersIcon,
            Trash2Icon,
            SettingsIcon,
            SectionTitle,
            MobileHeader,
            PageHeader,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
                plan: undefined,
            }
        },
        created() {
            axios.get('/api/plans/' + this.$route.params.id)
                .then(response => {
                    this.plan = response.data.data
                    this.isLoading = false
                })
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
