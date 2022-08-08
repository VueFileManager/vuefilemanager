<template>
    <PageTab v-if="!isLoading">
        <!--Cron check-->
        <div class="card shadow-card">
            <FormLabel icon="info">Cron</FormLabel>

            <div class="lg:flex lg:space-y-0 space-y-3 items-center justify-between">
                <div class="text-left">
                    <b class="block text-sm font-bold">Cron Jobs</b>
                    <small v-if="!cron.running" class="text-xs text-gray-600 pt-1 block leading-normal">
                        We detect, your cron jobs probably doesn't work correctly, please check it.
                    </small>
                    <small v-if="cron.running" class="text-xs text-gray-600 pt-1 block leading-normal">
                        Latest Update: {{ cron.lastUpdate }}
                    </small>
                </div>
                <div class="flex items-center">
                    <check-icon v-if="cron.running" size="16" class="vue-feather text-green-600 dark:text-green-600" />
                    <x-icon v-if="!cron.running" size="16" class="vue-feather text-red-600 dark:text-red-600" />

                    <span
                        class="ml-3 text-sm font-bold"
                        :class="cron.running ? 'text-green-600 dark:text-green-600' : 'text-red-600 dark:text-red-600'"
                    >
                        {{ cron.running ? 'Working correctly' : "Doesn't work" }}
                    </span>
                </div>
            </div>

			<div v-if="!cron.running" class="mt-8">
				<AppInputText
					:title="$t('Shared Web Hosting (Cpanel, Plesk, etc...) Command')"
					:description="$t('Proposed command for Shared Web Hosting (Cpanel, Plesk, etc...)')"
				>
					<CopyInput size="small" :str="cron.command.shared" />
				</AppInputText>

				<AppInputText
					:title="$t('Crontab Command')"
					:description="$t('Proposed command for crontab. Available only for setup via linux terminal.')"
					:is-last="true"
				>
					<CopyInput size="small" :str="cron.command.vps" />
				</AppInputText>
			</div>
        </div>

		<!--Broadcasting-->
        <div v-if="config.broadcasting" class="card shadow-card">
            <FormLabel icon="info">Broadcasting</FormLabel>

            <div class="lg:flex lg:space-y-0 space-y-3 items-center justify-between">
                <div class="text-left">
                    <b class="block text-sm font-bold">Websocket connection</b>
                    <small class="text-xs text-gray-600 pt-1 block leading-normal">
                        Here you can test websocket connection by sending test event.
                    </small>
                </div>
                <div class="flex items-center">
                    <ButtonBase @click.native="testWebsocketConnection" class="w-full sm:w-auto" button-style="theme">
                        {{ $t('Send Test Event') }}
                    </ButtonBase>
                </div>
            </div>
        </div>

        <!--Logs-->
        <div class="card shadow-card">
            <FormLabel icon="list">Latest Server Logs</FormLabel>

            <InfoBox v-if="!logs.length" class="!mb-0">
                <p v-html="$t('there_is_not_log')"></p>
            </InfoBox>

            <div
                v-if="logs.length"
                v-for="(log, i) in logs"
                :key="i"
                class="flex items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <div class="text-left">
                    <b class="block text-sm font-bold">
						{{ log }}
					</b>
                </div>
				<div @click="downloadLog(log)" class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-green-100 dark:bg-2x-dark-foreground cursor-pointer">
					<DownloadCloudIcon size="15" class="opacity-75" />
				</div>
            </div>
        </div>

        <!--Database Backups check-->
        <div class="card shadow-card">
            <FormLabel icon="database"> Latest Database Backups </FormLabel>

            <InfoBox v-if="!backups.length" class="!mb-0">
                <p v-html="$t('there_is_not_database_backup')"></p>
            </InfoBox>

            <InfoBox v-if="backups.length" class="!mb-3">
                <p v-html="$t('backup_path')"></p>
            </InfoBox>

            <div
                v-if="backups.length"
                v-for="(filename, i) in backups"
                :key="i"
                class="md:flex md:space-y-0 space-y-3 items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <div class="text-left">
                    <b class="block text-sm font-bold">{{ filename }}</b>
                </div>
                <div class="flex items-center">
                    <check-icon size="16" class="vue-feather text-green-600 dark:text-green-600" />

                    <span class="ml-3 text-sm font-bold text-green-600 dark:text-green-600"> Stored Successfully </span>
                </div>
            </div>
        </div>

        <!--Writable directories-->
        <div class="card shadow-card">
            <FormLabel icon="database">Writable Permission</FormLabel>

            <div
                v-for="(isWritable, file, i) in writable"
                :key="i"
                class="flex md:space-y-0 space-y-3 items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <div class="text-left">
                    <b class="block text-sm font-bold">/{{ file }}</b>
                </div>
                <div class="flex items-center">
                    <check-icon v-if="isWritable" size="16" class="vue-feather text-green-600 dark:text-green-600" />
                    <x-icon v-if="!isWritable" size="16" class="vue-feather text-red-600 dark:text-red-600" />

                    <span :class="{'text-green-600 dark:text-green-600': isWritable, 'text-red-600 dark:text-red-600': !isWritable}" class="ml-3 text-sm font-bold ">
						{{ isWritable ? 'Writable' : 'Unwritable'}}
					</span>
                </div>
            </div>
        </div>

        <!--PHP version and ini check-->
        <div class="card shadow-card">
            <FormLabel icon="info"> PHP Settings </FormLabel>

            <div
                class="flex items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <div class="text-left">
                    <b class="block text-sm font-bold">PHP Version</b>
                    <small v-if="!phpVersion.acceptable" class="text-xs text-gray-600 pt-1 block leading-normal">
                        You need PHP version at least {{ phpVersion.minimal }}.
                    </small>
                </div>
                <div class="flex items-center">
                    <check-icon
                        v-if="phpVersion.acceptable"
                        size="16"
                        class="vue-feather text-green-600 dark:text-green-600"
                    />
                    <x-icon
                        v-if="!phpVersion.acceptable"
                        size="16"
                        class="vue-feather text-red-600 dark:text-red-600"
                    />

                    <span
                        class="ml-3 text-sm font-bold"
                        :class="
                            phpVersion.acceptable
                                ? 'text-green-600 dark:text-green-600'
                                : 'text-red-600 dark:text-red-600'
                        "
                    >
                        {{ phpVersion.current }}
                    </span>
                </div>
            </div>

            <div
                v-for="(values, setting, i) in ini"
                :key="i"
                class="flex items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <div class="text-left">
                    <b class="block text-sm font-bold">{{ setting }}</b>
                    <small v-if="!values.status" class="text-xs text-gray-600 pt-1 block leading-normal">
                        You must set this value at least {{ values.minimal }}.
                    </small>
                </div>
                <div class="flex items-center">
                    <check-icon v-if="values.status" size="16" class="vue-feather text-green-600 dark:text-green-600" />
                    <x-icon v-if="!values.status" size="16" class="vue-feather text-red-600 dark:text-red-600" />

                    <span
                        class="ml-3 text-sm font-bold"
                        :class="values.status ? 'text-green-600 dark:text-green-600' : 'text-red-600 dark:text-red-600'"
                    >
                        {{ values.current }}{{ setting !== 'max_execution_time' ? 'M' : '' }}
                    </span>
                </div>
            </div>
        </div>

        <!--PHP Extension info-->
        <div class="card shadow-card">
            <FormLabel icon="info"> PHP Extensions </FormLabel>

            <div
                v-if="modules"
                v-for="(value, module, i) in modules"
                :key="i"
                class="flex items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
            >
                <b class="text-sm font-bold">
                    {{ module }}
                </b>
                <div class="flex items-center">
                    <check-icon v-if="value" size="16" class="vue-feather text-green-600 dark:text-green-600" />
                    <x-icon v-if="!value" size="16" class="vue-feather text-red-600 dark:text-red-600" />

                    <span
                        class="ml-3 text-sm font-bold"
                        :class="value ? 'text-green-600 dark:text-green-600' : 'text-red-600 dark:text-red-600'"
                    >
                        {{ value ? 'Module Installed' : 'You have to install this module' }}
                    </span>
                </div>
            </div>
        </div>
    </PageTab>
</template>

<script>
import AppInputText from "../../../../components/Forms/Layouts/AppInputText";
import CopyInput from "../../../../components/Inputs/CopyInput";
import { CheckIcon, XIcon, DownloadCloudIcon } from 'vue-feather-icons'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import { mapGetters } from 'vuex'
import axios from 'axios'
import ButtonBase from "../../../../components/UI/Buttons/ButtonBase";

export default {
    name: 'Server',
    components: {
		AppInputText,
		CopyInput,
		ButtonBase,
        FormLabel,
        InfoBox,
        PageTab,
		DownloadCloudIcon,
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
            logs: undefined,
			writable: undefined,
        }
    },
	methods: {
		testWebsocketConnection() {
			this.$store.dispatch('testConnection')
		},
		downloadLog(log) {

			let anchor = document.createElement('a')

			anchor.href = `/admin/log/${log}`
			anchor.download = log

			document.body.appendChild(anchor)

			anchor.click()
		}
	},
    created() {
        // Get status
        axios.get('/api/admin/status').then((response) => {
            this.isLoading = false
            this.ini = response.data.ini
            this.cron = response.data.cron
            this.modules = response.data.modules
            this.phpVersion = response.data.php_version
            this.backups = response.data.backups
            this.logs = response.data.logs
            this.writable = response.data.writable
        })

        // Ping API
        axios
            .get('/api/ping')
            .then((response) => {
                this.apiRunning = response.data === 'pong'
            })
            .catch(() => {
                this.apiRunning = false
            })
    },
}
</script>
