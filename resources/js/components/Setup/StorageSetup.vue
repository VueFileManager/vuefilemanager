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
					:is-last="true"
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
	</div>
</template>

<script>
	import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
	import SelectInput from '../Others/Forms/SelectInput'
	import AppInputText from '../Admin/AppInputText'
	import FormLabel from '../Others/Forms/FormLabel'

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
					key: undefined,
					secret: undefined,
					endpoint: undefined,
					region: undefined,
					bucket: undefined,
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
			}
		}
	}
</script>