<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon text-theme" />
                <h1>Server Check</h1>
                <h2>At first, we have to check if all modules and setup is ready for running VueFileManager.</h2>
            </div>

            <div class="form block-form">

				<!--PHP Extension info-->
                <FormLabel>Required PHP Extensions</FormLabel>
                <InfoBox>
                    <p>Those PHP modules are needed for accurate running VueFileManager on your server, please check and install if some is missing.</p>
                </InfoBox>

				<ul v-if="modules" class="check-list">
					<li v-for="(value, module, i) in modules" :key="i" class="check-item">
						<div class="content">
							<b class="parameter capitalize">{{ module }}</b>
						</div>
						<div class="status" :class="value ? 'success' : 'danger'">
							<check-icon v-if="value" size="16" />
							<x-icon v-if="! value" size="16" />
							<span class="note">{{ value ? 'Module Installed' : 'Missing Module' }}</span>
						</div>
					</li>
				</ul>

				<!--PHP version and ini check-->
                <FormLabel>PHP Version and php.ini</FormLabel>
                <InfoBox>
                    <p>Those PHP settings are needed for accurate running VueFileManager on your server, please check and tweak in your php.ini if needed.</p>
                </InfoBox>

				<ul class="check-list">
					<li class="check-item">
						<div class="content">
							<b class="parameter">PHP Version</b>
							<small v-if="! phpVersion.acceptable" class="help">You need PHP version at least {{ phpVersion.minimal }}.</small>
						</div>
						<div class="status" :class="phpVersion.acceptable ? 'success' : 'danger'">
							<check-icon v-if="phpVersion.acceptable" size="16" />
							<x-icon v-if="! phpVersion.acceptable" size="16" />
							<span class="note">{{ phpVersion.current }}</span>
						</div>
					</li>

					<li v-for="(values, setting, i) in ini" :key="i" class="check-item">
						<div class="content">
							<b class="parameter">{{ setting }}</b>
							<small v-if="! values.status" class="help">We recommend set this value at least {{ values.minimal }}.</small>
						</div>
						<div class="status" :class="values.status ? 'success' : 'danger'">
							<check-icon v-if="values.status" size="16" />
							<x-icon v-if="! values.status" size="16" />
							<span class="note">{{ values.current }}{{ setting !== 'max_execution_time' ? 'M' : '' }}</span>
						</div>
					</li>
				</ul>

				<!--API check-->
				<FormLabel>API</FormLabel>
                <InfoBox>
                    <p>The check, if your domain is set correctly.</p>
                </InfoBox>

				<ul class="check-list">
					<li class="check-item">
						<div class="content">
							<b class="parameter">API</b>
							<small v-if="isCheckedAPI && ! apiRunning" class="help">We detect, your domain root is not set correctly, please check it.</small>
						</div>
						<div v-if="isCheckedAPI" class="status" :class="apiRunning ? 'success' : 'danger'">
							<check-icon v-if="apiRunning" size="16" />
							<x-icon v-if="! apiRunning" size="16" />
							<span class="note">{{ apiRunning ? 'Working correctly' : "Doesn't work" }}</span>
						</div>
						<div v-if="! isCheckedAPI" class="status">
							<span class="note">Checking your API...</span>
						</div>
					</li>
				</ul>

                <InfoBox v-if="isError" type="error" style="margin-bottom: 10px">
					<p>We can't proceed to the next step because there are unresolved issues. Please solve it at first and next continue.</p>
                </InfoBox>

                <div class="submit-wrapper">
                    <AuthButton @click.native="lastCheckBeforeNextPage" icon="chevron-right" text="Awesome, I'm done!" :loading="isLoading" :disabled="isLoading" />
                </div>
            </div>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import AuthContent from '/resources/js/components/Auth/AuthContent'
	import AuthButton from '/resources/js/components/Auth/AuthButton'
	import {SettingsIcon} from 'vue-feather-icons'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import axios from 'axios'
	import {
		CheckIcon,
		XIcon,
	} from 'vue-feather-icons'

	export default {
		name: 'StatusCheck',
		components: {
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
				let modulesCheck = Object
					.values(this.$root.$data.config.statusCheck.modules)
					.every(module => module === true)

				let iniCheck = Object
					.values(this.$root.$data.config.statusCheck.ini)
					.every(setting => setting.status === true)

				if (modulesCheck && iniCheck && this.apiRunning && this.phpVersion.acceptable) {
					this.$router.push({name: 'PurchaseCode'})
				} else {
					this.isError = true
				}
			},
			pingAPI() {
				axios.get('/api/setup/ping')
					.then(response => {
						if (response.data === 'pong') {
							this.apiRunning = true
						} else {
							this.apiRunning = false
						}
					})
					.catch(() => {
						this.apiRunning = false
					})
			}
		},
		created() {
			this.$scrollTop()
			this.pingAPI()
		}
	}
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_forms';
	@import '/resources/sass/vuefilemanager/_auth';
	@import '/resources/sass/vuefilemanager/_setup_wizard';

	.check-list {
		display: block;
		border-radius: 8px;
		box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
		padding: 5px 20px;
		margin-bottom: 50px;

		.check-item {
			padding: 12px 0;
			display: flex;
			align-items: center;
			justify-content: space-between;
			border-bottom: 1px solid $light_mode_border;

			&:last-child {
				border-bottom: none;
			}
		}

		.status {
			display: flex;
			align-items: center;

			.note {
				margin-left: 10px;
				@include font-size(12);
				font-weight: 600;
				color: $text-muted;
			}

			&.success {
				.note {
					color: #00BC7E;
				}

				polyline {
					color: #00BC7E;
				}
			}

			&.danger {
				.note {
					color: #fd397a;
				}

				line {
					color: #fd397a;
				}
			}
		}

		.parameter {
			@include font-size(14);
		}

		.help {
			@include font-size(12);
			color: $text-muted;
			display: block;
		}
	}
</style>
