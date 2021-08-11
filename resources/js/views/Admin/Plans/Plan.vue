<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$t($router.currentRoute.meta.title)"/>
            <PageHeader :can-back="true" :title="$t($router.currentRoute.meta.title)"/>

            <div class="content-page">

                <!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'PlanSettings', params: {id: plan.id}}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <settings-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_page_plans.tabs.settings') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanSubscribers', params: {id: plan.id}}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <users-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_page_plans.tabs.subscribers') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PlanDelete', params: {id: plan.id}}"
                                 class="menu-list-item link link border-bottom-theme">
                        <div class="icon text-theme">
                            <trash2-icon size="17" />
                        </div>
                        <div class="label text-theme">
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
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import Spinner from '/resources/js/components/FilesView/Spinner'
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
            axios.get('/api/admin/plans/' + this.$route.params.id)
                .then(response => {
                    this.plan = response.data.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

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

    .dark-mode {
        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

</style>
