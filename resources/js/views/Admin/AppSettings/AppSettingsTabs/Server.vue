<template>
    <PageTab v-if="! isLoading">
		<!--Cron check-->
		<div class="card shadow-card">
			<FormLabel icon="info">
				Cron
			</FormLabel>

			<div class="flex items-center justify-between">
				<div class="text-left">
					<b class="text-sm font-bold block">Cron Jobs</b>
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

		<!--Database Backups check-->
		<div class="card shadow-card">
			<FormLabel icon="database">
				Latest Database Backups
			</FormLabel>

			<InfoBox v-if="! backups.length" class="!mb-0">
				<p v-html="$t('There is not any database backup stored.')"></p>
			</InfoBox>

			<InfoBox v-if="backups.length" class="!mb-3">
				<p v-html="$t('You can find your backups in <b>/storage/app/app-backups</b>.')"></p>
			</InfoBox>

			<div v-if="backups.length" v-for="(filename, i) in backups" :key="i" class="py-3 flex items-center justify-between border-b border-dashed border-light dark:border-opacity-5">
				<div class="text-left">
					<b class="text-sm font-bold block">{{ filename }}</b>
				</div>
				<div class="flex items-center">
					<check-icon size="16" class="vue-feather text-theme"/>

					<span class="ml-3 text-sm font-bold text-green-600">
						Stored Successfully
					</span>
				</div>
			</div>
		</div>

		<!--PHP version and ini check-->
		<div class="card shadow-card">
			<FormLabel icon="info">
				PHP Settings
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
			<FormLabel icon="info">
				PHP Extensions
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
    </PageTab>
</template>

<script>
import InfoBox from "../../../../components/Others/Forms/InfoBox"
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import PageTab from '../../../../components/Others/Layout/PageTab'
import { CheckIcon, XIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import axios from "axios";

export default {
    name: 'Server',
    components: {
        FormLabel,
		InfoBox,
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
			backups: undefined,
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
				this.backups = response.data.backups
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
