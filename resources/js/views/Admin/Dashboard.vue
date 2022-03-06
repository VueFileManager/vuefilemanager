<template>
    <div id="single-page">
        <div id="page-content" v-if="!isLoading && data">
            <!--Headline-->
            <div v-if="config.isAdminVueFileManagerBar" class="mb-4 hidden justify-between md:mb-6 md:block md:flex">
                <!--VueFileManager logo-->
                <a href="https://vuefilemanager.com" target="_blank">
                    <img
                        src="/assets/images/vuefilemanager-horizontal-logo.svg"
                        alt="VueFileManager"
                        class="light-mode"
                    />
                </a>

                <!--App Info-->
                <div class="mt-4 flex items-center md:mt-0">
                    <a
                        href="https://gist.github.com/MakingCG/9c07f8af392081ae5d5290d920a79b5d"
                        target="_blank"
                        class="mr-4 inline-block"
                    >
                        <span class="text-sm font-bold"> {{ $t('admin_page_dashboard.version') }}: </span>
                        <ColorLabel color="purple">
                            {{ data.app.version }}
                        </ColorLabel>
                    </a>
                    <a
                        href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986"
                        target="_blank"
                        class="mr-4 inline-block"
                    >
                        <span class="text-sm font-bold"> {{ $t('admin_page_dashboard.license') }}: </span>
                        <ColorLabel color="purple">
                            {{ data.app.license }}
                        </ColorLabel>
                    </a>
                    <b v-if="data.app.license === 'extended'" class="mr-4 inline-block">
                        <span class="text-sm font-bold"> {{ $t('Subscription') }}: </span>
                        <ColorLabel color="purple">
                            {{ config.subscriptionType }}
                        </ColorLabel>
                    </b>
                    <a
                        href="https://bit.ly/VueFileManager-survey"
                        target="_blank"
                        class="bg-theme-100 ml-8 inline-block hidden items-center rounded-lg py-1.5 px-3 md:flex lg:ml-4"
                    >
                        <thumbs-up-icon size="15" class="vue-feather text-theme mr-2.5" />
                        <span class="text-theme text-sm font-bold">
                            {{ $t('Write a Feedback') }}
                        </span>
                    </a>
                </div>
            </div>

            <!--Create metered plan alert-->
            <div
                v-if="config.subscriptionType === 'metered' && config.isEmptyPlans"
                class="mb-6 flex items-center rounded-xl bg-rose-200 p-5 shadow-card"
            >
                <alert-octagon-icon size="18" class="vue-feather mr-4 shrink-0 text-rose-700" />
                <p class="text-sm text-rose-700">
                    As you installed app with metered subscription type, you have to
                    <router-link :to="{ name: 'CreateMeteredPlan' }" class="text-sm font-bold underline"
                        >create your plan</router-link
                    >
                    as soon as possible to prevent new user registration without automatically assigned subscription
                    plan.
                </p>
            </div>

            <!--Cron Alert-->
            <div
                v-if="!data.app.isRunningCron && !config.isDev"
                class="mb-6 flex items-center rounded-xl bg-rose-200 p-5 shadow-card"
            >
                <alert-octagon-icon size="18" class="vue-feather mr-4 shrink-0 text-rose-700" />
                <p class="text-sm text-rose-700">
                    We detect your cron jobs probably doesn't work correctly, please check it, you need it for running
                    app correctly. If you set your cron job, please get back one minute later.
                </p>
            </div>

            <!--Metric widgets-->
            <div class="mb-2 md:mb-6 md:flex md:space-x-6">
                <div class="card mb-4 w-full shadow-card md:mb-0">
                    <FormLabel icon="users">
                        {{ $t('Total Users') }}
                    </FormLabel>

                    <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                        {{ data.users.total }}
                    </b>

                    <router-link :to="{ name: 'Users' }" class="mt-6 flex items-center">
                        <span class="mr-2 whitespace-nowrap text-xs font-bold">
                            {{ $t('admin_page_dashboard.w_total_space.link') }}
                        </span>
                        <chevron-right-icon size="16" class="text-theme vue-feather" />
                    </router-link>
                </div>
                <div class="card mb-4 w-full shadow-card md:mb-0">
                    <FormLabel icon="hard-drive">
                        {{ $t('Total Storage') }}
                    </FormLabel>

                    <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                        {{ data.disk.used }}
                    </b>

                    <router-link :to="{ name: 'Users' }" class="mt-6 flex items-center">
                        <span class="mr-2 whitespace-nowrap text-xs font-bold">
                            {{ $t('admin_page_dashboard.w_total_space.link') }}
                        </span>
                        <chevron-right-icon size="16" class="text-theme vue-feather" />
                    </router-link>
                </div>
                <div v-if="config.subscriptionType !== 'none'" class="card mb-4 w-full shadow-card md:mb-0">
                    <FormLabel icon="dollar">
                        {{ $t('Earnings') }}
                    </FormLabel>

                    <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                        {{ data.app.earnings }}
                    </b>

                    <router-link :to="{ name: 'Invoices' }" class="mt-6 flex items-center">
                        <span class="mr-2 whitespace-nowrap text-xs font-bold">
                            {{ $t('Show all transactions') }}
                        </span>
                        <chevron-right-icon size="16" class="text-theme vue-feather" />
                    </router-link>
                </div>
            </div>

            <!--Upload bandwidth widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="hard-drive">
                    {{ $t('Upload') }}
                </FormLabel>

                <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                    {{ data.disk.upload.total }}
                </b>

                <b class="mb-3 mb-2 block text-sm dark:text-gray-500 text-gray-400">
                    {{ $t('In last 45 days') }}
                </b>

                <BarChart :data="data.disk.upload.records" />
            </div>

            <!--Download bandwidth widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="hard-drive">
                    {{ $t('Download') }}
                </FormLabel>

                <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                    {{ data.disk.download.total }}
                </b>

                <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                    {{ $t('In last 45 days') }}
                </b>

                <BarChart :data="data.disk.download.records" />
            </div>

            <!--Latest registration widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="users">
                    {{ $t('Latest Registrations') }}
                </FormLabel>

                <WidgetLatestRegistrations />
            </div>

            <!--Latest transactions widgets-->
            <div
                v-if="['fixed', 'metered'].includes(this.config.subscriptionType)"
                class="card mb-4 shadow-card md:mb-6"
            >
                <FormLabel icon="dollar">
                    {{ $t('Latest Transactions') }}
                </FormLabel>

                <WidgetLatestTransactions />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
import WidgetLatestRegistrations from '../../components/Admin/WidgetLatestRegistrations'
import ColorLabel from '../../components/Others/ColorLabel'
import { AlertOctagonIcon, ChevronRightIcon, ThumbsUpIcon } from 'vue-feather-icons'
import WidgetWrapper from '../../components/Admin/WidgetWrapper'
import Spinner from '../../components/FilesView/Spinner'
import FormLabel from '../../components/Others/Forms/FormLabel'
import BarChart from '../../components/UI/BarChart'
import { mapGetters } from 'vuex'
import axios from 'axios'
import WidgetLatestTransactions from '../../components/Admin/WidgetLatestTransactions'
import InfoBox from '../../components/Others/Forms/InfoBox'

export default {
    name: 'Dashboard',
    components: {
        InfoBox,
        WidgetLatestTransactions,
        WidgetLatestRegistrations,
        ChevronRightIcon,
        AlertOctagonIcon,
        WidgetWrapper,
        ThumbsUpIcon,
        ColorLabel,
        FormLabel,
        BarChart,
        Spinner,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            isLoading: false,
            data: undefined,
        }
    },
    created() {
        axios
            .get('/api/admin/dashboard')
            .then((response) => {
                this.data = response.data
            })
            .finally(() => {
                this.isLoading = false
            })
    },
}
</script>
