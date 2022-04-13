<template>
	<div>
		<FormLabel icon="hard-drive">
			{{ $te('storage_driver') ? $t('storage_driver') : 'Storage Setup' }}
		</FormLabel>

		<ValidationProvider
			tag="div"
			mode="passive"
			name="Storage Service"
			rules="required"
			v-slot="{ errors }"
		>
			<AppInputText title="Storage Service" :error="errors[0]" :is-last="storage.driver === 'local' || storage.driver === undefined">
				<SelectInput
					v-model="storage.driver"
					:options="storageServiceList"
					placeholder="Select your storage service"
					:isError="errors[0]"
				/>
			</AppInputText>
		</ValidationProvider>

		<div v-if="s3PredefinedList.includes(storage.driver)">
			<ValidationProvider tag="div" mode="passive" name="Key" rules="required" v-slot="{ errors }">
				<AppInputText title="Key" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="storage.s3.key"
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
						v-model="storage.s3.secret"
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
						v-model="storage.s3.region"
						:options="regionList"
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
						v-model="storage.s3.region"
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
						v-model="storage.s3.endpoint"
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
					:is-last="true"
					:error="errors[0]"
				>
					<input
						class="focus-border-theme input-dark"
						v-model="storage.s3.bucket"
						placeholder="Type your bucket name"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
		</div>

		<div v-if="storage.driver === 'ftp'">
			<ValidationProvider tag="div" mode="passive" name="FTP Host" rules="required" v-slot="{ errors }">
				<AppInputText title="FTP Host" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="storage.ftp.host"
						placeholder="Type your ftp host..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
			<ValidationProvider tag="div" mode="passive" name="FTP Username" rules="required" v-slot="{ errors }">
				<AppInputText title="FTP Username" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="storage.ftp.user"
						placeholder="Type your ftp username..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
			<ValidationProvider tag="div" mode="passive" name="FTP Password" rules="required" v-slot="{ errors }">
				<AppInputText title="FTP Password" :error="errors[0]" :is-last="true">
					<input
						class="focus-border-theme input-dark"
						v-model="storage.ftp.password"
						placeholder="Type your ftp password..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
		</div>
	</div>
</template>

<script>
	import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
	import SelectInput from '../Inputs/SelectInput'
	import AppInputText from './Layouts/AppInputText'
	import FormLabel from '../UI/Labels/FormLabel'

	export default {
		name: 'StorageSetup',
		components: {
			ValidationObserver,
			ValidationProvider,
			AppInputText,
			SelectInput,
			FormLabel,
		},
		watch: {
			storage: {
				handler(newValue, oldValue) {
					this.$emit('input', newValue)
				},
				deep: true
			},
			'storage.driver': function () {
				this.storage.s3.region = undefined
			},
			'storage.s3.region': function (val) {
				this.storage.s3.endpoint = {
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
			s3Regions() {
				return this.$store.getters.s3Regions
			},
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
			},
		},
		data() {
			return {
				storage: {
					driver: undefined,
					s3: {
						key: undefined,
						secret: undefined,
						endpoint: undefined,
						region: undefined,
						bucket: undefined,
					},
					ftp: {
						host: undefined,
						user: undefined,
						password: undefined,
					}
				},
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
				s3PredefinedList: [
					's3',
					'storj',
					'spaces',
					'wasabi',
					'backblaze',
					'oss',
					'other',
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
					{
						label: 'FTP',
						value: 'ftp',
					},
				],
			}
		}
	}
</script>