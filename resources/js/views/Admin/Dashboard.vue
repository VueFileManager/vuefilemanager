<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading && data">

			<!--Headline-->
            <div v-if="config.isAdminVueFileManagerBar" class="md:flex justify-between md:mb-6 mb-4">

				<!--VueFileManager logo-->
				<a href="https://vuefilemanager.com" target="_blank">
					<img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager" class="light-mode">
				</a>

				<!--App Info-->
                <div class="flex items-center md:mt-0 mt-4">
                    <a href="https://gist.github.com/MakingCG/9c07f8af392081ae5d5290d920a79b5d" target="_blank" class="inline-block mr-4">
                        <span class="font-bold text-sm">
							{{ $t('admin_page_dashboard.version') }}:
						</span>
                        <ColorLabel color="purple">
                            {{ data.app.version }}
                        </ColorLabel>
                    </a>
                    <a href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986" target="_blank" class="inline-block mr-4">
                        <span class="font-bold text-sm">
							{{ $t('admin_page_dashboard.license') }}:
						</span>
                        <ColorLabel color="purple">
                            {{ data.app.license }}
                        </ColorLabel>
                    </a>
                    <a href="https://bit.ly/VueFileManager-survey" target="_blank" class="items-center inline-block rounded-lg py-1.5 px-3 ml-8 bg-theme-100 md:flex hidden">
						<thumbs-up-icon size="15" class="vue-feather text-theme mr-2.5"/>
                        <span class="font-bold text-sm text-theme">
                            {{ $t('Write a Feedback') }}
                        </span>
                    </a>
                </div>
            </div>

			<!--Metric widgets-->
            <div class="md:flex md:space-x-6 md:mb-6 mb-2">
				<div class="w-full md:mb-0 mb-4 card shadow-card">
					<FormLabel icon="users">
						{{ $t('Total Users') }}
					</FormLabel>

					<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
						{{ data.users.total }}
					</b>

					<router-link :to="{name: 'Users'}" class="flex items-center mt-6">
						<span class="text-xs font-bold mr-2 whitespace-nowrap">
							{{ $t('admin_page_dashboard.w_total_space.link') }}
						</span>
						<chevron-right-icon size="16" class="text-theme vue-feather"/>
					</router-link>
				</div>
				<div class="w-full md:mb-0 mb-4 card shadow-card">
					<FormLabel icon="hard-drive">
						{{ $t('Total Storage') }}
					</FormLabel>

					<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
						{{ data.disk.used }}
					</b>

					<router-link :to="{name: 'Users'}" class="flex items-center mt-6">
						<span class="text-xs font-bold mr-2 whitespace-nowrap">
							{{ $t('admin_page_dashboard.w_total_space.link') }}
						</span>
						<chevron-right-icon size="16" class="text-theme vue-feather"/>
					</router-link>
				</div>
				<div v-if="config.subscriptionType !== 'none'" class="w-full md:mb-0 mb-4 card shadow-card">
					<FormLabel icon="dollar">
						{{ $t('Earnings') }}
					</FormLabel>

					<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
						{{ data.app.earnings }}
					</b>

					<router-link :to="{name: 'Invoices'}" class="flex items-center mt-6">
						<span class="text-xs font-bold mr-2 whitespace-nowrap">
							{{ $t('Show all transactions') }}
						</span>
						<chevron-right-icon size="16" class="text-theme vue-feather"/>
					</router-link>
				</div>
            </div>

			<!--Upload bandwidth widgets-->
			<div class="card shadow-card md:mb-6 mb-4">
				<FormLabel icon="hard-drive">
					{{ $t('Upload') }}
				</FormLabel>

				<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
					{{ data.disk.upload.total }}
				</b>

				<b class="mb-3 block text-sm text-gray-400 mb-2">
					{{ $t('In last 45 days') }}
				</b>

				<BarChart :data="data.disk.upload.records" />
			</div>

			<!--Download bandwidth widgets-->
			<div class="card shadow-card md:mb-6 mb-4">
				<FormLabel icon="hard-drive">
					{{ $t('Download') }}
				</FormLabel>

				<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
					{{ data.disk.download.total }}
				</b>

				<b class="mb-3 block text-sm text-gray-400 mb-5">
					{{ $t('In last 45 days') }}
				</b>

				<BarChart :data="data.disk.download.records" />
			</div>

			<!--Latest registration widgets-->
			<div class="card shadow-card md:mb-6 mb-4">
				<FormLabel icon="users">
					{{ $t('Latest Registrations') }}
				</FormLabel>

				<WidgetLatestRegistrations />
			</div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import WidgetLatestRegistrations from '/resources/js/components/Admin/WidgetLatestRegistrations'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import {ChevronRightIcon, ThumbsUpIcon} from "vue-feather-icons"
	import WidgetWrapper from "../../components/Admin/WidgetWrapper"
    import Spinner from '/resources/js/components/FilesView/Spinner'
	import FormLabel from "../../components/Others/Forms/FormLabel"
	import BarChart from "../../components/UI/BarChart"
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Dashboard',
        components: {
            WidgetLatestRegistrations,
			ChevronRightIcon,
			WidgetWrapper,
			ThumbsUpIcon,
            ColorLabel,
			FormLabel,
			BarChart,
            Spinner,
        },
        computed: {
            ...mapGetters([
				'config'
			]),
        },
        data() {
            return {
                isLoading: false,
                data: undefined,
            }
        },
        created() {
            axios.get('/api/admin/dashboard')
                .then(response => {
                    this.data = response.data
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    }
</script>
