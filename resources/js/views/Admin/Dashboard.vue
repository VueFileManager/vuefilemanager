<template>
    <div>
        <div id="page-content" v-if="!isLoading && data">

            <!--Headline-->
            <AppSpecification v-if="config.isAdminVueFileManagerBar" :data="data" class="hidden lg:flex" />
            <AppSpecification v-if="config.isAdminVueFileManagerBar" :data="data" class="card shadow-card lg:hidden" />

            <!--Cron Alert-->
			<AlertBox v-if="!data.app.isRunningCron && !config.isDev" color="rose">
				We detect your cron jobs probably doesn't work correctly, please check it, you need it for running app correctly. If you set your cron job, please get back one minute later.
			</AlertBox>

            <!--Metric widgets-->
            <div class="mb-2 md:mb-6 md:flex md:space-x-6">
                <div class="card mb-4 w-full shadow-card md:mb-0">
                    <FormLabel icon="users">
                        {{ $t('total_users') }}
                    </FormLabel>

                    <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                        {{ data.users.total }}
                    </b>

                    <router-link :to="{ name: 'Users' }" class="mt-6 flex items-center">
                        <span class="mr-2 whitespace-nowrap text-xs font-bold">
                            {{ $t('show_all_users') }}
                        </span>
                        <chevron-right-icon size="16" class="text-theme vue-feather" />
                    </router-link>
                </div>
                <div class="card mb-4 w-full shadow-card md:mb-0">
                    <FormLabel icon="hard-drive">
                        {{ $t('total_storage') }}
                    </FormLabel>

                    <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                        {{ data.disk.used }}
                    </b>

                    <router-link :to="{ name: 'Users' }" class="mt-6 flex items-center">
                        <span class="mr-2 whitespace-nowrap text-xs font-bold">
                            {{ $t('show_all_users') }}
                        </span>
                        <chevron-right-icon size="16" class="text-theme vue-feather" />
                    </router-link>
                </div>
            </div>

            <!--Upload bandwidth widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="hard-drive">
                    {{ $t('upload') }}
                </FormLabel>

                <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                    {{ data.disk.upload.total }}
                </b>

                <b class="mb-3 mb-2 block text-sm dark:text-gray-500 text-gray-400">
                    {{ $t('in_last_x_days') }}
                </b>

                <BarChart :data="data.disk.upload.records" />
            </div>

            <!--Download bandwidth widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="hard-drive">
                    {{ $t('download') }}
                </FormLabel>

                <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                    {{ data.disk.download.total }}
                </b>

                <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                    {{ $t('in_last_x_days') }}
                </b>

                <BarChart :data="data.disk.download.records" />
            </div>

            <!--Latest registration widgets-->
            <div class="card mb-4 shadow-card md:mb-6">
                <FormLabel icon="users">
                    {{ $t('latest_registrations') }}
                </FormLabel>

                <WidgetLatestRegistrations />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner />
        </div>
    </div>
</template>

<script>
import WidgetLatestRegistrations from '../../components/Dashboard/Widgets/WidgetLatestRegistrations'
import {ChevronRightIcon} from 'vue-feather-icons'
import Spinner from '../../components/UI/Others/Spinner'
import FormLabel from '../../components/UI/Labels/FormLabel'
import BarChart from '../../components/UI/BarChart/BarChart'
import {mapGetters} from 'vuex'
import axios from 'axios'
import AlertBox from "../../components/UI/Others/AlertBox";
import AppSpecification from "../../components/Dashboard/AppSpecification";

export default {
    name: 'Dashboard',
    components: {
		AppSpecification,
		AlertBox,
        WidgetLatestRegistrations,
        ChevronRightIcon,
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
