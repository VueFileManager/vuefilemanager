<template>
    <PageTab :is-loading="isLoading">

		<!--Broadcasting setup-->
        <ValidationObserver
			@submit.prevent="broadcastSetupSubmit"
			ref="broadcastSetup"
			v-slot="{ invalid }"
			tag="form"
			class="card shadow-card"
		>
            <FormLabel icon="wifi">
				{{ $t('broadcasting') }}
			</FormLabel>

            <ValidationProvider tag="div" mode="passive" name="Broadcast Driver" rules="required" v-slot="{ errors }">
                <AppInputText title="Broadcast Driver" :error="errors[0]">
                    <SelectInput
						v-model="broadcast.driver"
						:options="broadcastDrivers"
						placeholder="Select your broadcast driver"
						:isError="errors[0]"
					/>
                </AppInputText>
            </ValidationProvider>

			<div v-if="broadcast.driver === 'native'">
				<ValidationProvider tag="div" mode="passive" name="Host" rules="required" v-slot="{ errors }">
                    <AppInputText title="Hostname or IP" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model="broadcast.host"
							placeholder="Type your hostname or IP"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Port" rules="required" v-slot="{ errors }">
                    <AppInputText title="Port" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model="broadcast.port"
							placeholder="Type your port"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>
			</div>

            <div v-if="broadcast.driver === 'pusher'">
                <ValidationProvider tag="div" mode="passive" name="App ID" rules="required" v-slot="{ errors }">
                    <AppInputText title="App ID" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model="broadcast.id"
							placeholder="Type your app id"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Key" rules="required" v-slot="{ errors }">
                    <AppInputText title="Key" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model="broadcast.key"
							placeholder="Paste your key"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                    <AppInputText title="Secret" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model="broadcast.secret"
							placeholder="Paste your secret"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Cluster" rules="required" v-slot="{ errors }">
                    <AppInputText title="Cluster" :error="errors[0]">
                        <SelectInput
							v-model="broadcast.cluster"
							:options="pusherClusters"
							placeholder="Select your cluster"
							:isError="errors[0]"
						/>
                    </AppInputText>
                </ValidationProvider>
            </div>

            <ButtonBase
				:loading="isSendingBroadcastForm"
				:disabled="isSendingBroadcastForm"
				type="submit"
				button-style="theme"
				class="w-full sm:w-auto"
			>
                {{ $t('save_broadcast_settings') }}
            </ButtonBase>
        </ValidationObserver>

		<!--Storage setup-->
		<ValidationObserver
			@submit.prevent="storageSetupSubmit"
			ref="storageSetup"
			v-slot="{ invalid }"
			tag="form"
			class="card shadow-card"
		>
			<FormLabel icon="hard-drive">
				{{ $t('storage_driver') }}
			</FormLabel>

			<ValidationProvider
				tag="div"
				mode="passive"
				name="Storage Service"
				rules="required"
				v-slot="{ errors }"
			>
				<AppInputText title="Storage Service" :error="errors[0]">
					<SelectInput
						v-model="storage.driver"
						:options="storageServiceList"
						placeholder="Select your storage service"
						:isError="errors[0]"
					/>
				</AppInputText>
			</ValidationProvider>

			<div v-if="storage.driver !== 'local' && storage.driver">
				<ValidationProvider tag="div" mode="passive" name="Key" rules="required" v-slot="{ errors }">
					<AppInputText title="Key" :error="errors[0]">
						<input
							class="focus-border-theme input-dark"
							v-model="storage.key"
							placeholder="Paste your key"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
					<AppInputText title="Secret" :error="errors[0]">
						<input
							class="focus-border-theme input-dark"
							v-model="storage.secret"
							placeholder="Paste your secret"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
					</AppInputText>
				</ValidationProvider>

				<!--List Region-->
				<ValidationProvider v-if="storage.driver !== 'other'" tag="div" mode="passive" name="Region" rules="required" v-slot="{ errors }">
					<AppInputText
						title="Region"
						description="Select your region where is your bucket created."
						:error="errors[0]"
					>
						<SelectInput
							v-model="storage.region"
							:options="regionList"
							:default="storage.region"
							placeholder="Select your region"
							:isError="errors[0]"
						/>
					</AppInputText>
				</ValidationProvider>

				<!--Input Region-->
				<ValidationProvider v-if="storage.driver === 'other'" tag="div" mode="passive" name="Region" rules="required" v-slot="{ errors }">
					<AppInputText
						title="Region"
						description="Type your region where is your bucket created."
						:error="errors[0]"
					>
						<input
							class="focus-border-theme input-dark"
							v-model="storage.region"
							placeholder="Type your region"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
							:readonly="storage.driver !== 'other'"
						/>
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider
					tag="div"
					mode="passive"
					name="Endpoint"
					rules="required"
					v-slot="{ errors }"
				>
					<AppInputText title="Endpoint URL" :description="endpointUrlDescription" :error="errors[0]">
						<input
							class="focus-border-theme input-dark"
							v-model="storage.endpoint"
							placeholder="Type your endpoint"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
							:readonly="storage.driver !== 'other'"
						/>
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Bucket" rules="required" v-slot="{ errors }">
					<AppInputText
						title="Bucket"
						description="Type your created unique bucket name"
						:error="errors[0]"
					>
						<input
							class="focus-border-theme input-dark"
							v-model="storage.bucket"
							placeholder="Type your bucket name"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
					</AppInputText>
				</ValidationProvider>
			</div>

			<ButtonBase
				:loading="isSendingStorageForm"
				:disabled="isSendingStorageForm"
				type="submit"
				button-style="theme"
				class="w-full sm:w-auto"
			>
                {{ $t('save_storage_settings') }}
            </ButtonBase>
		</ValidationObserver>

		<!--Mail setup-->
        <ValidationObserver
            @submit.prevent="emailSetupSubmit"
            ref="EmailSetup"
            v-slot="{ invalid }"
            tag="form"
            class="card shadow-card"
        >
            <FormLabel icon="mail">{{ $t('mail_driver') }}</FormLabel>

            <ValidationProvider tag="div" mode="passive" name="Mail Driver" rules="required" v-slot="{ errors }">
                <AppInputText title="Mail Driver" :error="errors[0]">
                    <SelectInput
                        v-model="mailDriver"
                        :default="mailDriver"
                        :options="mailDriverList"
                        :placeholder="$t('select_mail_driver')"
                        :isError="errors[0]"
                    />
                </AppInputText>
            </ValidationProvider>

            <div v-if="mailDriver === 'smtp'">
                <ValidationProvider tag="div" mode="passive" name="Mail Host" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Host" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.host"
                            placeholder="Type your mail host"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Port" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Port" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.port"
                            placeholder="Type your mail port"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Username" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Username" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model.lazy="smtp.username"
                            placeholder="Type your mail username"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Mail Password" rules="required" v-slot="{ errors }">
                    <AppInputText title="Mail Password" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="smtp.password"
                            placeholder="Type your mail password"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Mail Encryption"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Mail Encryption" :error="errors[0]">
                        <SelectInput
                            v-model="smtp.encryption"
                            :default="smtp.encryption"
                            :options="mailEncryptionList"
                            placeholder="Select your mail encryption"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>

				<ValidationProvider v-if="shouldSetSMTPEmail" tag="div" mode="passive" name="Mail From Address" rules="required|email" v-slot="{ errors }">
                    <AppInputText title="Mail" :error="errors[0]">
                        <input
							class="focus-border-theme input-dark"
							v-model.trim="smtp.email"
							placeholder="Type your mail from address"
							type="text"
							:class="{ '!border-rose-600': errors[0] }"
						/>
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'mailgun'">
                <ValidationProvider tag="div" mode="passive" name="Domain" rules="required" v-slot="{ errors }">
                    <AppInputText title="Domain" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.domain"
                            placeholder="Type your domain"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Secret" rules="required" v-slot="{ errors }">
                    <AppInputText title="Secret" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.secret"
                            placeholder="Type your secret"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Endpoint" rules="required" v-slot="{ errors }">
                    <AppInputText title="Endpoint" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="mailgun.endpoint"
                            placeholder="Type your endpoint"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'postmark'">
                <ValidationProvider tag="div" mode="passive" name="Token" rules="required" v-slot="{ errors }">
                    <AppInputText title="Token" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="postmark.token"
                            placeholder="Type your token"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <div v-if="mailDriver === 'ses'">
                <ValidationProvider tag="div" mode="passive" name="Access Key" rules="required" v-slot="{ errors }">
                    <AppInputText title="Access Key" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.access_key"
                            placeholder="Type your access key"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Secret Access Key"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Secret Access Key" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.secret_access_key"
                            placeholder="Type your secret access key"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Default Region" rules="required" v-slot="{ errors }">
                    <AppInputText title="Default Region" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.default_region"
                            placeholder="Type your default region"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Session Token" v-slot="{ errors }">
                    <AppInputText title="Session Token (optional)" :error="errors[0]">
                        <input
                            class="focus-border-theme input-dark"
                            v-model="ses.session_token"
                            placeholder="Type your session token"
                            type="text"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <ButtonBase
                :loading="isSendingEmailForm"
                :disabled="isSendingEmailForm"
                type="submit"
                button-style="theme"
                class="w-full sm:w-auto"
            >
                {{ $t('admin_settings.email.save_button') }}
            </ButtonBase>
        </ValidationObserver>
    </PageTab>
</template>

<script>
import AppInputText from '../../../../components/Admin/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Others/Layout/PageTabGroup'
import SelectInput from '../../../../components/Others/Forms/SelectInput'
import ImageInput from '../../../../components/Others/Forms/ImageInput'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import ButtonBase from '../../../../components/FilesView/ButtonBase'
import SetupBox from '../../../../components/Others/Forms/SetupBox'
import PageTab from '../../../../components/Others/Layout/PageTab'
import InfoBox from '../../../../components/Others/Forms/InfoBox'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../../../bus'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
    name: 'AppEnvironment',
    components: {
        AppInputText,
        ValidationObserver,
        ValidationProvider,
        PageTabGroup,
        SelectInput,
        ImageInput,
        ButtonBase,
        FormLabel,
        SetupBox,
        required,
        PageTab,
        InfoBox,
    },
	watch: {
		'smtp.username': function (val) {
			if (this.$isValidEmail(val)) {
				this.smtp.email = undefined
				this.shouldSetSMTPEmail = false
			} else {
				this.shouldSetSMTPEmail = true
			}
		},
		'storage.driver': function () {
			this.storage.region = undefined
		},
		'storage.region': function (val) {
			this.storage.endpoint = {
				storj: 'https://gateway.' + val + '.storjshare.io',
				spaces: 'https://' + val + '.digitaloceanspaces.com',
				wasabi: 'https://s3.' + val + '.wasabisys.com',
				backblaze: 'https://s3.' + val + '.backblazeb2.com',
				oss: 'https://' + val + '.aliyuncs.com',
				s3: 'https://s3.' + val + '.amazonaws.com',
				other: undefined,
			}[this.storage.driver]
		},
	},
    computed: {
        ...mapGetters(['mailEncryptionList', 'mailDriverList']),
		regionList() {
			return {
				storj: this.storjRegions,
				s3: this.s3Regions,
				spaces: this.digitalOceanRegions,
				wasabi: this.wasabiRegions,
				backblaze: this.backblazeRegions,
				oss: this.ossRegions,
				other: undefined,
			}[this.storage.driver]
		},
		endpointUrlDescription() {
			return this.storage.driver === 'other' ? this.$t('The endpoint url should start with https://') : ''
		}
    },
    data() {
        return {
			shouldSetSMTPEmail: false,
            isLoading: false,
            isSendingEmailForm: false,
            isSendingStorageForm: false,
			isSendingBroadcastForm: false,
            mailDriver: undefined,
			broadcastDrivers: [
				{
					label: 'Pusher',
					value: 'pusher',
				},
				{
					label: 'VueFileManager Broadcast Server',
					value: 'native',
				},
				{
					label: 'None',
					value: 'none',
				},
			],
			pusherClusters: [
				{
					label: 'US East (N. Virginia)',
					value: 'mt1',
				},
				{
					label: 'Asia Pacific (Singapore)',
					value: 'ap1',
				},
				{
					label: 'Asia Pacific (Mumbai)',
					value: 'ap2',
				},
				{
					label: 'US East (Ohio)',
					value: 'us2',
				},
				{
					label: 'Asia Pacific (Tokyo)',
					value: 'ap3',
				},
				{
					label: 'US West (Oregon)',
					value: 'us3',
				},
				{
					label: 'Asia Pacific (Sydney)',
					value: 'ap4',
				},
				{
					label: 'EU (Ireland)',
					value: 'eu',
				},
				{
					label: 'South America (São Paulo)',
					value: 'sa1',
				},
			],
			ossRegions: [
				{
					label: 'China (Hangzhou)',
					value: 'oss-cn-hangzhou',
				},
				{
					label: 'China (Shanghai)',
					value: 'oss-cn-shanghai',
				},
				{
					label: 'China (Qingdao)',
					value: 'oss-cn-qingdao',
				},
				{
					label: 'China (Beijing)',
					value: 'oss-cn-beijing',
				},
				{
					label: 'China (Zhangjiakou)',
					value: 'oss-cn-zhangjiakou',
				},
				{
					label: 'China (Hohhot)',
					value: 'oss-cn-huhehaote',
				},
				{
					label: 'China (Ulanqab)',
					value: 'oss-cn-wulanchabu',
				},
				{
					label: 'China (Shenzhen)',
					value: 'oss-cn-shenzhen',
				},
				{
					label: 'China (Heyuan)',
					value: 'oss-cn-heyuan',
				},
				{
					label: 'China (Guangzhou)',
					value: 'oss-cn-guangzhou',
				},
				{
					label: 'China (Chengdu)',
					value: 'oss-cn-chengdu',
				},
				{
					label: 'China (Hong Kong)',
					value: 'oss-cn-hongkong',
				},
			],
			wasabiRegions: [
				{
					label: 'us-west-1',
					value: 'us-west-1',
				},
				{
					label: 'ap-northeast-1',
					value: 'ap-northeast-1',
				},
				{
					label: 'ap-northeast-2',
					value: 'ap-northeast-2',
				},
				{
					label: 'ca-central-1',
					value: 'ca-central-1',
				},
				{
					label: 'eu-central-1',
					value: 'eu-central-1',
				},
				{
					label: 'eu-central-2',
					value: 'eu-central-2',
				},
				{
					label: 'eu-west-1',
					value: 'eu-west-1',
				},
				{
					label: 'eu-west-2',
					value: 'eu-west-2',
				},
				{
					label: 'us-central-1',
					value: 'us-central-1',
				},
				{
					label: 'us-east-1',
					value: 'us-east-1',
				},
				{
					label: 'us-east-2',
					value: 'us-east-2',
				},
			],
			storjRegions: [
				{
					label: 'EU Central 1',
					value: 'eu1',
				},
				{
					label: 'US Central 1',
					value: 'us1',
				},
				{
					label: 'AP Central 1',
					value: 'ap1',
				},
			],
			backblazeRegions: [
				{
					label: 'us-west-001',
					value: 'us-west-001',
				},
				{
					label: 'us-west-002',
					value: 'us-west-002',
				},
				{
					label: 'us-west-004',
					value: 'us-west-004',
				},
				{
					label: 'eu-central-003',
					value: 'eu-central-003',
				},
			],
			digitalOceanRegions: [
				{
					label: 'New York',
					value: 'nyc3',
				},
				{
					label: 'San Francisco',
					value: 'sfo2',
				},
				{
					label: 'Amsterdam',
					value: 'ams3',
				},
				{
					label: 'Singapore',
					value: 'sgp1',
				},
				{
					label: 'Frankfurt',
					value: 'fra1',
				},
			],
			s3Regions: [
				{
					label: 'us-east-1',
					value: 'us-east-1',
				},
				{
					label: 'us-east-2',
					value: 'us-east-2',
				},
				{
					label: 'us-west-1',
					value: 'us-west-1',
				},
				{
					label: 'us-west-2',
					value: 'us-west-2',
				},
				{
					label: 'af-south-1',
					value: 'af-south-1',
				},
				{
					label: 'ap-east-1',
					value: 'ap-east-1',
				},
				{
					label: 'ap-south-1',
					value: 'ap-south-1',
				},
				{
					label: 'ap-northeast-2',
					value: 'ap-northeast-2',
				},
				{
					label: 'ap-southeast-1',
					value: 'ap-southeast-1',
				},
				{
					label: 'ap-southeast-2',
					value: 'ap-southeast-2',
				},
				{
					label: 'ap-northeast-1',
					value: 'ap-northeast-1',
				},
				{
					label: 'ca-central-1',
					value: 'ca-central-1',
				},
				{
					label: 'eu-central-1',
					value: 'eu-central-1',
				},
				{
					label: 'eu-west-1',
					value: 'eu-west-1',
				},
				{
					label: 'eu-west-2',
					value: 'eu-west-2',
				},
				{
					label: 'eu-south-1',
					value: 'eu-south-1',
				},
				{
					label: 'eu-west-3',
					value: 'eu-west-3',
				},
				{
					label: 'eu-north-1',
					value: 'eu-north-1',
				},
				{
					label: 'me-south-1',
					value: 'me-south-1',
				},
				{
					label: 'sa-east-1',
					value: 'sa-east-1',
				},
			],
			storageServiceList: [
				{
					label: 'Local Driver',
					value: 'local',
				},
				{
					label: 'Amazon Web Services S3',
					value: 's3',
				},
				{
					label: 'Storj',
					value: 'storj',
				},
				{
					label: 'Digital Ocean Spaces',
					value: 'spaces',
				},
				{
					label: 'Object Cloud Storage by Wasabi',
					value: 'wasabi',
				},
				{
					label: 'Backblaze B2 Cloud Storage',
					value: 'backblaze',
				},
				{
					label: 'Alibaba Cloud OSS',
					value: 'oss',
				},
				{
					label: 'Other S3 Compatible Service',
					value: 'other',
				},
			],
			storage: {
				driver: undefined,
				key: undefined,
				secret: undefined,
				endpoint: undefined,
				region: undefined,
				bucket: undefined,
			},
            ses: {
                access_key: undefined,
                secret_access_key: undefined,
                default_region: undefined,
                session_token: undefined,
            },
            smtp: {
                host: undefined,
                port: undefined,
				email: undefined,
                username: undefined,
                password: undefined,
                encryption: undefined,
            },
            mailgun: {
                domain: undefined,
                secret: undefined,
                endpoint: undefined,
            },
            postmark: {
                token: undefined,
            },
			broadcast: {
				driver: undefined,
				id: undefined,
				key: undefined,
				secret: undefined,
				cluster: undefined,
				port: undefined,
				host: undefined,
			}
        }
    },
    methods: {
		async broadcastSetupSubmit() {
			// Validate fields
			const isValid = await this.$refs.broadcastSetup.validate()

			if (!isValid) return

			// Start loading
			this.isSendingBroadcastForm = true

			axios
				.post('/api/admin/settings/broadcast', {...this.broadcast})
				.then(() => {
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('broadcast_driver_updated'),
					})
				})
				.catch(() => {
					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('popup_error.title'),
					})
				})
				.finally(() => {
					this.isSendingBroadcastForm = false

					this.broadcast = {
						driver: undefined,
						id: undefined,
						key: undefined,
						secret: undefined,
						cluster: undefined,
						host: undefined,
						port: undefined,
					}
				})
		},
		async storageSetupSubmit() {
			// Validate fields
			const isValid = await this.$refs.storageSetup.validate()

			if (!isValid) return

			// Start loading
			this.isSendingStorageForm = true

			axios
				.post('/api/admin/settings/storage', {
					storage: this.storage
				})
				.then(() => {
					this.storage = {
						driver: undefined,
						key: undefined,
						secret: undefined,
						endpoint: undefined,
						region: undefined,
						bucket: undefined,
					}

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('storage_driver_updated'),
					})
				})
				.catch((error) => {
					if (error.response.status === 401 && error.response.data.type === 's3-connection-error') {
						events.$emit('alert:open', {
							title: 'S3 Connection Error - Wrong Credentials or Not Permitted',
							message: error.response.data.message,
						})
					} else {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					}
				})
				.finally(() => {
					this.isSendingStorageForm = false
				})
		},
        async emailSetupSubmit() {
            // Validate fields
            const isValid = await this.$refs.EmailSetup.validate()

            if (!isValid) return

            // Start loading
            this.isSendingEmailForm = true

            axios
                .post('/api/admin/settings/email', {
                    environment: this.environment,
                    storage: this.storage,
                    mailDriver: this.mailDriver,
                    smtp: this.smtp,
                    mailgun: this.mailgun,
                    ses: this.ses,
                    postmark: this.postmark,
                })
                .then(() => {
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.email_set'),
                    })
                })
                .catch((error) => {
					if (error.response.status === 401 && error.response.data.type === 'mailer-connection-error') {
						events.$emit('alert:open', {
							title: 'Mailer Connection Error - Wrong Credentials',
							message: error.response.data.message,
						})
					} else {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					}
                })
                .finally(() => {
                    this.isSendingEmailForm = false
                })
        },
    },
}
</script>
