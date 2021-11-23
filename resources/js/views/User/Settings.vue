<template>
    <PageTab>
        <div class="card shadow-card">
            <FormLabel>{{ $t('user_settings.title_account') }}</FormLabel>
            <AppInputText :title="$t('page_registration.label_email')">
                <input :value="userInfo.email"
					   :placeholder="$t('page_registration.placeholder_email')"
					   type="email"
					   disabled
					   class="focus-border-theme input-dark"
			   >
            </AppInputText>
            <AppInputText :title="$t('page_registration.label_name')" :is-last="true">
                <input @keyup="changeUserName"
					   v-model="userInfo.name"
					   :placeholder="$t('page_registration.placeholder_name')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>{{ $t('user_settings.timezone') }}</FormLabel>
            <AppInputText :title="$t('GMT')" :is-last="true">
                <SelectInput @input="$updateText('/user/settings', 'timezone', userInfo.timezone)"
							 v-model="userInfo.timezone"
							 :default="userInfo.timezone"
							 :options="timezones"
							 :placeholder="$t('user_settings.timezone_plac')" />
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('user_settings.title_billing') }}
            </FormLabel>
            <AppInputText :title="$t('user_settings.name')">
                <input @keyup="$updateText('/user/settings', 'name', billingInfo.name)"
					   v-model="billingInfo.name"
					   :placeholder="$t('user_settings.name_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.address')">
                <input @keyup="$updateText('/user/settings', 'address', billingInfo.address)"
					   v-model="billingInfo.address"
					   :placeholder="$t('user_settings.address_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <div class="flex space-x-4">
                <AppInputText :title="$t('user_settings.city')" class="w-full">
                    <input @keyup="$updateText('/user/settings', 'city', billingInfo.city)"
						   v-model="billingInfo.city"
						   :placeholder="$t('user_settings.city_plac')"
						   type="text"
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
                <AppInputText :title="$t('user_settings.postal_code')" class="w-full">
                    <input @keyup="$updateText('/user/settings', 'postal_code', billingInfo.postal_code)"
						   v-model="billingInfo.postal_code"
						   :placeholder="$t('user_settings.postal_code_plac')"
						   type="text"
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </div>
            <AppInputText :title="$t('user_settings.country')">
                <SelectInput @input="$updateText('/user/settings', 'country', billingInfo.country)"
							 v-model="billingInfo.country"
							 :default="billingInfo.country"
							 :options="countries"
							 :placeholder="$t('user_settings.country_plac')"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.state')" :description="$t('State, county, province, or region.')">
                <input @keyup="$updateText('/user/settings', 'state', billingInfo.state)"
					   v-model="billingInfo.state"
					   :placeholder="$t('user_settings.state_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.phone_number')" :is-last="true">
                <input @keyup="$updateText('/user/settings', 'phone_number', billingInfo.phone_number)"
					   v-model="billingInfo.phone_number"
					   :placeholder="$t('user_settings.phone_number_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
	import AppInputText from "../../components/Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import PageHeader from '/resources/js/components/Others/PageHeader'
	import ThemeLabel from '/resources/js/components/Others/ThemeLabel'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {debounce} from 'lodash'

	export default {
		name: 'Settings',
		props: [
			'user'
		],
		components: {
			AppInputText,
			ValidationProvider,
			ValidationObserver,
			PageTabGroup,
			MobileHeader,
			SelectInput,
			PageHeader,
			ButtonBase,
			ThemeLabel,
			FormLabel,
			required,
			PageTab,
		},
		computed: {
			...mapGetters(['config', 'countries', 'timezones']),
		},
		data() {
			return {
				userInfo: undefined,
				billingInfo: undefined,
				isLoading: false,
			}
		},
		methods: {
			changeUserName() {
				this.$store.commit('UPDATE_NAME', this.userInfo.name)
				this.$updateText('/user/settings', 'name', this.userInfo.name)
			}
		},
		created() {

			this.userInfo = {
				timezone: this.user.data.relationships.settings.data.attributes.timezone,
				name: this.user.data.relationships.settings.data.attributes.name,
				email: this.user.data.attributes.email
			}

			this.billingInfo = {
				name: this.user.data.relationships.settings.data.attributes.name,
				address: this.user.data.relationships.settings.data.attributes.address,
				state: this.user.data.relationships.settings.data.attributes.state,
				city: this.user.data.relationships.settings.data.attributes.city,
				postal_code: this.user.data.relationships.settings.data.attributes.postal_code,
				country: this.user.data.relationships.settings.data.attributes.country,
				phone_number: this.user.data.relationships.settings.data.attributes.phone_number,
			}
		}
	}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';
	@import '/resources/sass/vuefilemanager/_forms';

	.block-form {
		max-width: 100%;
	}

</style>
