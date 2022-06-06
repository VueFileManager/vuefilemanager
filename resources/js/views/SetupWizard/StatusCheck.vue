<template>
    <AuthContentWrapper ref="auth">
        <!--Server Check-->
        <AuthContent :visible="true" class="mt-6 mb-12 !max-w-2xl">
            <Headline
                class="mx-auto !mb-10 max-w-screen-sm"
                title="Server Check"
                description="At first, we have to check if all modules and setup is ready for running VueFileManager"
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

			<a href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986">
				<AlertBox color="rose" class="text-left">
					<p class="text-rose-500">Please make sure you have only legal copy of VueFileManager <b class="text-rose-500 underline">purchased from CodeCanyon</b>. Any illegal copy can contain malicious software, bugs and others security issues which exposes your files to data breach.</p>
				</AlertBox>
			</a>

            <!--PHP version and ini check-->
            <div class="card shadow-card">
                <FormLabel>PHP Setup</FormLabel>

                <InfoBox class="!mb-2">
                    <p>
                        Those PHP settings are needed for accurate running VueFileManager on your server, please check
                        and tweak in your php.ini if needed.
                    </p>
                </InfoBox>

                <div
                    class="flex items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
                >
                    <div class="text-left">
                        <b class="block text-sm font-bold">PHP Version</b>
                        <small v-if="!phpVersion.acceptable" class="dark:text-gray-500 text-xs text-gray-600 pt-1 block leading-normal">
                            You need PHP version at least {{ phpVersion.minimal }}.
                        </small>
                    </div>
                    <div class="flex items-center">
                        <check-icon v-if="phpVersion.acceptable" size="16" class="vue-feather dark:text-theme text-theme" />
                        <x-icon v-if="!phpVersion.acceptable" size="16" class="vue-feather dark:text-red-600 text-red-600" />

                        <span
                            class="ml-3 text-sm font-bold"
                            :class="phpVersion.acceptable ? 'dark:text-green-600 text-green-600' : 'dark:text-red-600 text-red-600'"
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
                        <small v-if="!values.status" class="dark:text-gray-500 text-xs text-gray-600 pt-1 block leading-normal">
                            You must set this value at least {{ values.minimal }}.
                        </small>
                    </div>
                    <div class="flex items-center">
                        <check-icon v-if="values.status" size="16" class="vue-feather dark:text-theme text-theme" />
                        <x-icon v-if="!values.status" size="16" class="vue-feather dark:text-red-600 text-red-600" />

                        <span class="ml-3 text-sm font-bold" :class="values.status ? 'dark:text-green-600 text-green-600' : 'dark:text-red-600 text-red-600'">
                            {{ values.current }}{{ setting !== 'max_execution_time' ? 'M' : '' }}
                        </span>
                    </div>
                </div>
            </div>

            <!--PHP Extension info-->
            <div class="card shadow-card">
                <FormLabel> Required PHP Extensions </FormLabel>

                <InfoBox class="!mb-2">
                    <p>
                        Those PHP modules are needed for accurate running VueFileManager on your server, please check
                        and install if some is missing.
                    </p>
                </InfoBox>

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
                        <check-icon v-if="value" size="16" class="vue-feather dark:text-theme text-theme" />
                        <x-icon v-if="!value" size="16" class="vue-feather dark:text-red-600 text-red-600" />

                        <span class="ml-3 text-sm font-bold" :class="value ? 'dark:text-green-600 text-green-600' : 'dark:text-red-600 text-red-600'">
                            {{ value ? 'Module Installed' : 'You have to install this module' }}
                        </span>
                    </div>
                </div>
            </div>

			<!--Writable directories-->
			<div class="card shadow-card">
				<FormLabel icon="database">Writable Permission</FormLabel>

				<div
					v-for="(isWritable, file, i) in writable"
					:key="i"
					class="flex items-center md:space-y-0 space-y-3 items-center justify-between border-b border-dashed border-light py-3 dark:border-opacity-5"
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

            <!--API check-->
            <div class="card shadow-card">
                <FormLabel> API </FormLabel>

                <InfoBox class="!mb-2">
                    <p>The check, if your domain is set correctly.</p>
                </InfoBox>

                <div class="flex items-center justify-between pt-3">
                    <div class="text-left">
                        <b class="block text-sm font-bold">API</b>
                        <small v-if="isCheckedAPI && !apiRunning" class="dark:text-gray-500 text-xs text-gray-600 pt-1 block leading-normal">
                            We detect, your domain root is not set correctly, please check it.
                        </small>
                    </div>
                    <div v-if="isCheckedAPI" class="flex items-center">
                        <check-icon v-if="apiRunning" size="16" class="vue-feather dark:text-theme text-theme" />
                        <x-icon v-if="!apiRunning" size="16" class="vue-feather dark:text-red-600 text-red-600" />

                        <span class="ml-3 text-sm font-bold" :class="apiRunning ? 'dark:text-green-600 text-green-600' : 'dark:text-red-600 text-red-600'">
                            {{ apiRunning ? 'Working correctly' : "Doesn't work" }}
                        </span>
                    </div>
                    <span v-if="!isCheckedAPI" class="ml-3 text-sm font-bold text-gray-600">Checking your API...</span>
                </div>

            </div>

			<InfoBox v-if="isError" type="error">
				<p>
					We can't proceed to the next step because there are unresolved issues. Please solve it at first
					and next continue.
				</p>
			</InfoBox>

            <AuthButton
                @click.native="lastCheckBeforeNextPage"
                class="w-full justify-center"
                icon="chevron-right"
                text="Awesome, I'm done!"
                :loading="isLoading"
                :disabled="isLoading"
            />
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import SelectInput from '../../components/Inputs/SelectInput'
import FormLabel from '../../components/UI/Labels/FormLabel'
import InfoBox from '../../components/UI/Others/InfoBox'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import { required } from 'vee-validate/dist/rules'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { mapGetters } from 'vuex'
import axios from 'axios'
import { CheckIcon, XIcon, SettingsIcon } from 'vue-feather-icons'
import AlertBox from "../../components/UI/Others/AlertBox";

export default {
    name: 'StatusCheck',
    components: {
		AlertBox,
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        SettingsIcon,
        SelectInput,
        AuthContent,
        AuthButton,
        FormLabel,
        required,
        InfoBox,
        CheckIcon,
        Headline,
        XIcon,
    },
    computed: {
        modules() {
            return this.$root.$data.config.statusCheck.modules
        },
        ini() {
            return this.$root.$data.config.statusCheck.ini
        },
        phpVersion() {
            return this.$root.$data.config.statusCheck.php_version
        },
        writable() {
            return this.$root.$data.config.statusCheck.writable
        },
        isCheckedAPI() {
            return typeof this.apiRunning !== 'undefined'
        },
    },
    data() {
        return {
            isLoading: false,
            isError: false,
            apiRunning: undefined,
        }
    },
    methods: {
        lastCheckBeforeNextPage() {
            let modulesCheck = Object.values(this.$root.$data.config.statusCheck.modules).every(
                (module) => module === true
            )

            let iniCheck = Object.values(this.$root.$data.config.statusCheck.ini).every(
                (setting) => setting.status === true
            )

            let writeCheck = Object.values(this.$root.$data.config.statusCheck.writable).every(
                (directory) => directory === true
            )

            if (writeCheck && modulesCheck && iniCheck && this.apiRunning && this.phpVersion.acceptable) {
                this.$router.push({ name: 'PurchaseCode' })
            } else {
                this.isError = true
            }
        },
        pingAPI() {
            axios
                .get('/api/ping')
                .then((response) => {
                    this.apiRunning = response.data.message === 'pong'
                })
                .catch(() => {
                    this.apiRunning = false
                })
        },
    },
    created() {
        this.$scrollTop()
        this.pingAPI()
    },
}
</script>
