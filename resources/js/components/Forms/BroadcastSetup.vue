<template>
	<div>
		<FormLabel icon="wifi">
			{{ $te('broadcasting') ? $t('broadcasting') : 'Broadcasting' }}
		</FormLabel>

		<ValidationProvider
			tag="div"
			mode="passive"
			name="Broadcast Driver"
			rules="required"
			v-slot="{ errors }"
		>
			<AppInputText
				title="Broadcast Driver"
				:error="errors[0]"
				:is-last="broadcast.driver === 'none' || broadcast.driver === undefined"
			>
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

			<ValidationProvider tag="div" mode="passive" name="TLS" v-slot="{ errors }">
				<AppInputSwitch
					title="Required TLS Connection"
					description="When enabled, you must have installed ssl certificate on your server host"
					:is-last="true"
				>
					<SwitchInput v-model="broadcast.tls" :state="broadcast.tls" />
				</AppInputSwitch>
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

			<ValidationProvider
				tag="div"
				mode="passive"
				name="Cluster"
				rules="required"
				v-slot="{ errors }"
			>
				<AppInputText title="Cluster" :error="errors[0]" :is-last="true">
					<SelectInput
						v-model="broadcast.cluster"
						:options="pusherClusters"
						placeholder="Select your cluster"
						:isError="errors[0]"
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
	import AppInputSwitch from "./Layouts/AppInputSwitch";
	import SwitchInput from "../Inputs/SwitchInput";

	export default {
		name: 'BroadcastSetup',
		components: {
			SwitchInput,
			AppInputSwitch,
			ValidationObserver,
			ValidationProvider,
			AppInputText,
			SelectInput,
			FormLabel,
		},
		watch: {
			broadcast: {
				handler(newValue, oldValue) {
					this.$emit('input', newValue)
				},
				deep: true
			},

		},
		data() {
			return {
				broadcast: {
					driver: undefined,
					id: undefined,
					key: undefined,
					secret: undefined,
					cluster: undefined,
					tls: true,
				},
				broadcastDrivers: [
					{
						label: 'Pusher',
						value: 'pusher',
					},
					{
						label: 'VueFileManager',
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
			}
		}
	}
</script>