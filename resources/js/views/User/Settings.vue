<template>
    <div>
        <div class="card shadow-card">
            <FormLabel>
				{{ $t('Account Settings') }}
			</FormLabel>

			<div class="md:flex justify-items md:space-x-4">
				<AppInputText :title="$t('First Name')" class="w-full">
					<input
						@keyup="updateFirstName"
						v-model="user.data.relationships.settings.data.attributes.first_name"
						:placeholder="$t('page_registration.placeholder_name')"
						type="text"
						class="focus-border-theme input-dark"
					/>
				</AppInputText>
				<AppInputText :title="$t('Last Name')" class="w-full">
					<input
						@keyup="updateLastName"
						v-model="user.data.relationships.settings.data.attributes.last_name"
						:placeholder="$t('page_registration.placeholder_name')"
						type="text"
						class="focus-border-theme input-dark"
					/>
				</AppInputText>
			</div>

			<AppInputText :title="$t('GMT')" :is-last="true">
                <SelectInput
					@input="$updateText('/user/settings', 'timezone', user.data.relationships.settings.data.attributes.timezone)"
					v-model="user.data.relationships.settings.data.attributes.timezone"
					:default="user.data.relationships.settings.data.attributes.timezone"
					:options="timezones"
					:placeholder="$t('user_settings.timezone_plac')" />
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('user_settings.title_billing') }}
            </FormLabel>
            <AppInputText :title="$t('user_settings.address')">
                <input @keyup="$updateText('/user/settings', 'address', user.data.relationships.settings.data.attributes.address)"
					   v-model="user.data.relationships.settings.data.attributes.address"
					   :placeholder="$t('user_settings.address_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <div class="flex space-x-4">
                <AppInputText :title="$t('user_settings.city')" class="w-full">
                    <input @keyup="$updateText('/user/settings', 'city', user.data.relationships.settings.data.attributes.city)"
						   v-model="user.data.relationships.settings.data.attributes.city"
						   :placeholder="$t('user_settings.city_plac')"
						   type="text"
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
                <AppInputText :title="$t('user_settings.postal_code')" class="w-full">
                    <input @keyup="$updateText('/user/settings', 'postal_code', user.data.relationships.settings.data.attributes.postal_code)"
						   v-model="user.data.relationships.settings.data.attributes.postal_code"
						   :placeholder="$t('user_settings.postal_code_plac')"
						   type="text"
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </div>
            <AppInputText :title="$t('user_settings.country')">
                <SelectInput @input="$updateText('/user/settings', 'country', user.data.relationships.settings.data.attributes.country)"
							 v-model="user.data.relationships.settings.data.attributes.country"
							 :default="user.data.relationships.settings.data.attributes.country"
							 :options="countries"
							 :placeholder="$t('user_settings.country_plac')"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.state')" :description="$t('State, county, province, or region.')">
                <input @keyup="$updateText('/user/settings', 'state', user.data.relationships.settings.data.attributes.state)"
					   v-model="user.data.relationships.settings.data.attributes.state"
					   :placeholder="$t('user_settings.state_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.phone_number')" :is-last="true">
                <input @keyup="$updateText('/user/settings', 'phone_number', user.data.relationships.settings.data.attributes.phone_number)"
					   v-model="user.data.relationships.settings.data.attributes.phone_number"
					   :placeholder="$t('user_settings.phone_number_plac')"
					   type="text"
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>
    </div>
</template>

<script>
	import AppInputText from "../../components/Admin/AppInputText";
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'

	export default {
		name: 'Settings',
		components: {
			AppInputText,
			SelectInput,
			FormLabel,
			required,
		},
		computed: {
			...mapGetters([
				'countries',
				'timezones',
				'config',
			]),
		},
		data() {
			return {
				user: this.$store.getters.user,
				isLoading: false,
			}
		},
		methods: {
			updateFirstName() {
				this.$store.commit('UPDATE_FIRST_NAME', this.user.data.relationships.settings.data.attributes.first_name)
				this.$updateText('/user/settings', 'first_name', this.user.data.relationships.settings.data.attributes.first_name)
			},
			updateLastName() {
				this.$store.commit('UPDATE_LAST_NAME', this.user.data.relationships.settings.data.attributes.last_name)
				this.$updateText('/user/settings', 'last_name', this.user.data.relationships.settings.data.attributes.last_name)
			}
		},
	}
</script>
