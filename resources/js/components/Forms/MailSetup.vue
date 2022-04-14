<template>
	<div>
		<FormLabel icon="hard-drive">
			{{ $te('mail_driver') ? $t('mail_driver') : 'Mail Setup' }}
		</FormLabel>

		<ValidationProvider tag="div" mode="passive" name="Mail Driver" rules="required" v-slot="{ errors }">
			<AppInputText title="Mail Driver" :error="errors[0]" :is-last="mail.driver === undefined || mail.driver === 'log'">
				<SelectInput
					v-model="mail.driver"
					:default="mail.driver"
					:options="driversList"
					:placeholder="$te('select_mail_driver') ? $t('select_mail_driver') : 'Select your mail driver'"
					:isError="errors[0]"
				/>
			</AppInputText>
		</ValidationProvider>

		<div v-if="mail.driver === 'smtp'">
			<ValidationProvider tag="div" mode="passive" name="Mail Host" rules="required" v-slot="{ errors }">
				<AppInputText title="Mail Host" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.smtp.host"
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
						v-model="mail.smtp.port"
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
						v-model="mail.smtp.username"
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
						v-model="mail.smtp.password"
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
				<AppInputText title="Mail Encryption" :error="errors[0]" :is-last="! shouldSetSMTPEmail">
					<SelectInput
						v-model="mail.smtp.encryption"
						:default="mail.smtp.encryption"
						:options="mailEncryptionList"
						placeholder="Select your mail encryption"
						:isError="errors[0]"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider v-if="shouldSetSMTPEmail" tag="div" mode="passive" name="Mail From" rules="required|email" v-slot="{ errors }">
				<AppInputText title="Mail From Address" :error="errors[0]" :is-last="true">
					<input
						class="focus-border-theme input-dark"
						v-model.trim="mail.smtp.email"
						placeholder="Type your mail from address"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
		</div>

		<div v-if="mail.driver === 'mailgun'">
			<ValidationProvider tag="div" mode="passive" name="Domain" rules="required" v-slot="{ errors }">
				<AppInputText title="Domain" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.mailgun.domain"
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
						v-model="mail.mailgun.secret"
						placeholder="Type your secret"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Endpoint" rules="required" v-slot="{ errors }">
				<AppInputText title="Endpoint" :error="errors[0]">
					<SelectInput
						v-model="mail.mailgun.endpoint"
						:options="mailgunRegions"
						placeholder="Select your endpoint"
						:isError="errors[0]"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
				<AppInputText title="Sender (Email)" :error="errors[0]" :is-last="true">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.mailgun.sender"
						placeholder="Type your sender email..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
		</div>

		<div v-if="mail.driver === 'postmark'">
			<ValidationProvider tag="div" mode="passive" name="Token" rules="required" v-slot="{ errors }">
				<AppInputText title="Token" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.postmark.token"
						placeholder="Type your token"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
				<AppInputText title="Sender Signature (Email)" :error="errors[0]" :is-last="true">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.postmark.sender"
						placeholder="Type your sender signature..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>
		</div>

		<div v-if="mail.driver === 'ses'">
			<ValidationProvider tag="div" mode="passive" name="Access Key" rules="required" v-slot="{ errors }">
				<AppInputText title="Access Key" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.ses.access_key"
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
						v-model="mail.ses.secret_access_key"
						placeholder="Type your secret access key"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Default Region" rules="required" v-slot="{ errors }">
				<AppInputText title="Region" :error="errors[0]">
					<SelectInput
						v-model="mail.ses.default_region"
						:options="s3Regions"
						placeholder="Select your region"
						:isError="errors[0]"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Sender" rules="required|email" v-slot="{ errors }">
				<AppInputText title="Identity (Email)" :error="errors[0]">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.ses.sender"
						placeholder="Type your identity email..."
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
					/>
				</AppInputText>
			</ValidationProvider>

			<ValidationProvider tag="div" mode="passive" name="Session Token" v-slot="{ errors }">
				<AppInputText title="Session Token (optional)" :error="errors[0]" :is-last="true">
					<input
						class="focus-border-theme input-dark"
						v-model="mail.ses.session_token"
						placeholder="Type your session token"
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
		name: 'MailSetup',
		components: {
			ValidationObserver,
			ValidationProvider,
			AppInputText,
			SelectInput,
			FormLabel,
		},
		watch: {
			mail: {
				handler(newValue, oldValue) {
					this.$emit('input', newValue)
				},
				deep: true
			},
			'mail.smtp.username': function (val) {
				if (this.$isValidEmail(val)) {
					this.mail.smtp.email = undefined
					this.shouldSetSMTPEmail = false
				} else {
					this.shouldSetSMTPEmail = true
				}
			},
		},
		computed: {
			s3Regions() {
				return this.$store.getters.s3Regions
			},
		},
		data() {
			return {
				shouldSetSMTPEmail: false,
				mail: {
					driver: undefined,
					ses: {
						access_key: undefined,
						secret_access_key: undefined,
						default_region: undefined,
						session_token: undefined,
						sender: undefined,
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
						sender: undefined,
					},
					postmark: {
						token: undefined,
						sender: undefined,
					},
				},
				mailgunRegions: [
					{
						label: 'US Endpoint (api.mailgun.net)',
						value: 'api.mailgun.net',
					},
					{
						label: 'EU Endpoint (api.eu.mailgun.net)',
						value: 'api.eu.mailgun.net',
					},
				],
				mailEncryptionList: [
					{
						label: 'TLS',
						value: 'tls',
					},
					{
						label: 'SSL',
						value: 'ssl',
					},
					{
						label: 'None',
						value: '',
					},
				],
				driversList: [
					{
						label: 'SMTP',
						value: 'smtp',
					},
					{
						label: 'Mailgun',
						value: 'mailgun',
					},
					{
						label: 'SES',
						value: 'ses',
					},
					{
						label: 'Postmark',
						value: 'postmark',
					},
					{
						label: 'None',
						value: 'log',
					},
				],
			}
		}
	}
</script>