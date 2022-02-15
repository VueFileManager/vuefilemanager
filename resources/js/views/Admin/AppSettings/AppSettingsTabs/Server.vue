<template>
    <PageTab v-if="! isLoading">
		<!--PHP version and ini check-->
		<div class="card shadow-card">
			<FormLabel>
				PHP Version & php.ini
			</FormLabel>

			<div class="py-3 flex items-center justify-between border-b border-dashed border-light dark:border-opacity-5">
				<div class="text-left">
					<b class="text-sm font-bold block">PHP Version</b>
					<small v-if="!phpVersion.acceptable" class="text-xs text-gray-600">
						You need PHP version at least {{ phpVersion.minimal }}.
					</small>
				</div>
				<div class="flex items-center">
					<check-icon v-if="phpVersion.acceptable" size="16" class="vue-feather text-theme"/>
					<x-icon v-if="!phpVersion.acceptable" size="16" class="vue-feather text-red-600" />

					<span class="ml-3 text-sm font-bold" :class="phpVersion.acceptable ? 'text-green-600' : 'text-red-600'">
						{{ phpVersion.current }}
					</span>
				</div>
			</div>

			<div v-for="(values, setting, i) in ini" :key="i" class="py-3 flex items-center justify-between border-b border-dashed border-light dark:border-opacity-5">
				<div class="text-left">
					<b class="text-sm font-bold block">{{ setting }}</b>
					<small v-if="!values.status" class="text-xs text-gray-600">
						We recommend set this value at least {{ values.minimal }}.
					</small>
				</div>
				<div class="flex items-center">
					<check-icon v-if="values.status" size="16" class="vue-feather text-theme"/>
					<x-icon v-if="!values.status" size="16" class="vue-feather text-red-600" />

					<span class="ml-3 text-sm font-bold" :class="values.status ? 'text-green-600' : 'text-red-600'">
						{{ values.current }}{{ setting !== 'max_execution_time' ? 'M' : '' }}
					</span>
				</div>
			</div>
		</div>

		<!--PHP Extension info-->
		<div class="card shadow-card">
			<FormLabel>
				Required PHP Extensions
			</FormLabel>

			<div v-if="modules" v-for="(value, module, i) in modules" :key="i" class="py-3 flex items-center justify-between border-b border-dashed border-light dark:border-opacity-5">
				<b class="text-sm font-bold">
					{{ module }}
				</b>
				<div class="flex items-center">
					<check-icon v-if="value" size="16" class="vue-feather text-theme"/>
					<x-icon v-if="!value" size="16" class="vue-feather text-red-600"/>

					<span class="ml-3 text-sm font-bold" :class="value ? 'text-green-600' : 'text-red-600'">
						{{ value ? 'Module Installed' : 'Missing Module' }}
					</span>
				</div>
			</div>
		</div>

		<!--API check-->
		<div class="card shadow-card">
			<FormLabel>
				Others
			</FormLabel>

			<div class="py-3 flex items-center justify-between border-b border-dashed border-light dark:border-opacity-5">
				<div class="text-left">
					<b class="text-sm font-bold block">API</b>
					<small v-if="isCheckedAPI && !apiRunning" class="text-xs text-gray-600">
						We detect, your domain root is not set correctly, please check it.
					</small>
				</div>
				<div v-if="isCheckedAPI" class="flex items-center">
					<check-icon v-if="apiRunning" size="16" class="vue-feather text-theme"/>
					<x-icon v-if="!apiRunning" size="16" class="vue-feather text-red-600" />

					<span class="ml-3 text-sm font-bold" :class="apiRunning ? 'text-green-600' : 'text-red-600'">
						{{ apiRunning ? 'Working correctly' : "Doesn't work" }}
					</span>
				</div>
				<span v-if="!isCheckedAPI" class="ml-3 text-sm font-bold text-gray-600">Checking your API...</span>
			</div>

			<div class="pt-3 flex items-center justify-between">
				<div class="text-left">
					<b class="text-sm font-bold block">Cron</b>
					<small v-if="!cron.running" class="text-xs text-gray-600">
						We detect, your cron jobs probably doesn't work correctly, please check it.
					</small>
					<small v-if="cron.running" class="text-xs text-gray-600">
						Latest Update: {{ cron.lastUpdate }}
					</small>
				</div>
				<div class="flex items-center">
					<check-icon v-if="cron.running" size="16" class="vue-feather text-theme"/>
					<x-icon v-if="!cron.running" size="16" class="vue-feather text-red-600" />

					<span class="ml-3 text-sm font-bold" :class="cron.running ? 'text-green-600' : 'text-red-600'">
						{{ cron.running ? 'Working correctly' : "Doesn't work" }}
					</span>
				</div>
			</div>
		</div>
    </PageTab>
</template>

<script>
import { CheckIcon, XIcon } from 'vue-feather-icons'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import PageTab from '../../../../components/Others/Layout/PageTab'
import { mapGetters } from 'vuex'
import axios from "axios";

export default {
    name: 'Server',
    components: {
        FormLabel,
        PageTab,
		CheckIcon,
		XIcon,
    },
    computed: {
        ...mapGetters(['config']),
		isCheckedAPI() {
			return typeof this.apiRunning !== 'undefined'
		},
    },
    data() {
        return {
			isLoading: true,
			ini: undefined,
			cron: undefined,
			modules: undefined,
			phpVersion: undefined,
			apiRunning: undefined,
        }
    },
    created() {
		// Get status
		axios.get('/api/admin/status')
			.then(response => {
				this.isLoading = false
				this.ini = response.data.ini
				this.cron = response.data.cron
				this.modules = response.data.modules
				this.phpVersion = response.data.php_version
			})

		// Ping API
		axios
			.get('/api/ping')
			.then((response) => {
				this.apiRunning = response.data === 'pong';
			})
			.catch(() => {
				this.apiRunning = false
			})
    },
}
</script>
