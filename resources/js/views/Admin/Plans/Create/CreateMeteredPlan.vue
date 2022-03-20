<template>
    <ValidationObserver @submit.prevent="createPlan" ref="createPlan" v-slot="{ invalid }" tag="form">
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('details') }}
            </FormLabel>

			<!--Name-->
            <ValidationProvider tag="div" mode="passive" name="Name" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('name')">
                    <input
						v-model="plan.name"
						:placeholder="$t('plan_name')"
						type="text"
						:class="{ '!border-rose-600': errors[0] }"
						class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </ValidationProvider>

			<!--Description-->
            <ValidationProvider tag="div" mode="passive" name="Description" v-slot="{ errors }">
                <AppInputText :title="$t('description_optional')">
                    <textarea
						v-model="plan.description"
						:placeholder="$t('plan_description')"
						:class="{ '!border-rose-600': errors[0] }"
						class="focus-border-theme input-dark"
					></textarea>
                </AppInputText>
            </ValidationProvider>

			<!--Currency-->
            <ValidationProvider tag="div" mode="passive" name="Currency" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('currency')" class="w-full" :is-last="true">
                    <SelectInput
						v-model="plan.currency"
						:options="currencyList"
						:placeholder="$t('select_plan_currency')"
						:isError="errors[0]"
					/>
                </AppInputText>
            </ValidationProvider>
        </div>

        <div class="card shadow-card">
            <FormLabel>
                {{ $t('charged_features') }}
            </FormLabel>

			<!--Bandwidth-->
            <div>
                <AppInputSwitch
					:title="$t('bandwidth_per_gb')"
					:description="$t('bandwidth_per_gb_note')"
				>
                    <SwitchInput
						v-model="plan.features.bandwidth.active"
						class="switch"
						:state="plan.features.bandwidth.active"
					/>
                </AppInputSwitch>

                <ValidationProvider
					v-if="plan.features.bandwidth.active"
					class="-mt-3"
					tag="div"
					mode="passive"
					name="Bandwidth Price"
					rules="required"
					v-slot="{ errors }"
				>
                    <AppInputText class="w-full">
                        <input
							v-model="plan.features.bandwidth.per_unit"
							:placeholder="$t('type_bandwidth_price')"
							type="number"
							step="0.01"
							min="0.01"
							max="999999999999"
							:class="{ '!border-rose-600': errors[0] }"
							class="focus-border-theme input-dark"
						/>
                    </AppInputText>
                </ValidationProvider>
            </div>

			<!--Storage-->
            <div>
                <AppInputSwitch
					:title="$t('storage_per_gb')"
					:description="$t('storage_per_gb_note')"
				>
                    <SwitchInput
						v-model="plan.features.storage.active"
						class="switch"
						:state="plan.features.storage.active"
					/>
                </AppInputSwitch>
            </div>

            <ValidationProvider
				v-if="plan.features.storage.active"
				class="-mt-3"
				tag="div"
				mode="passive"
				name="Storage Price"
				rules="required"
				v-slot="{ errors }"
			>
                <AppInputText class="w-full">
                    <input
						v-model="plan.features.storage.per_unit"
						:placeholder="$t('type_storage_price')"
						type="number"
						step="0.01"
						min="0.01"
						max="999999999999"
						:class="{ '!border-rose-600': errors[0] }"
						class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </ValidationProvider>

			<!--Member-->
            <div>
                <AppInputSwitch
					:title="$t('member_per_unit')"
					:description="$t('member_per_unit_note')"
				>
                    <SwitchInput
						v-model="plan.features.member.active"
						class="switch"
						:state="plan.features.member.active"
					/>
                </AppInputSwitch>
            </div>

            <ValidationProvider
				v-if="plan.features.member.active"
				class="-mt-3"
				tag="div"
				mode="passive"
				name="Member Price"
				rules="required"
				v-slot="{ errors }"
			>
                <AppInputText class="w-full">
                    <input
						v-model="plan.features.member.per_unit"
						:placeholder="$t('type_member_price')"
						type="number"
						step="0.01"
						min="0.01"
						max="999999999999"
						:class="{ '!border-rose-600': errors[0] }"
						class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </ValidationProvider>

			<!--Flat Fee-->
            <div>
                <AppInputSwitch
					:title="$t('flat_fee_unit_gb')"
					:description="$t('flat_fee_unit_gb_note')"
					:is-last="!plan.features.flatFee.active"
				>
                    <SwitchInput
						v-model="plan.features.flatFee.active"
						class="switch"
						:state="plan.features.flatFee.active"
					/>
                </AppInputSwitch>

                <ValidationProvider
					v-if="plan.features.flatFee.active"
					class="-mt-3"
					tag="div"
					mode="passive"
					name="FlatFee Price"
					rules="required"
					v-slot="{ errors }"
				>
                    <AppInputText class="w-full" :is-last="true">
                        <input
							v-model="plan.features.flatFee.per_unit"
							:placeholder="$t('type_flat_fee_price')"
							type="number"
							step="0.01"
							min="0.01"
							max="999999999999"
							:class="{ '!border-rose-600': errors[0] }"
							class="focus-border-theme input-dark"
						/>
                    </AppInputText>
                </ValidationProvider>
            </div>
        </div>

        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full sm:w-auto">
            {{ $t('create_plan') }}
        </ButtonBase>
    </ValidationObserver>
</template>

<script>
import SwitchInput from '../../../../components/Others/Forms/SwitchInput'
import AppInputSwitch from '../../../../components/Admin/AppInputSwitch'
import AppInputText from '../../../../components/Admin/AppInputText'
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import SelectInput from '../../../../components/Others/Forms/SelectInput'
import ImageInput from '../../../../components/Others/Forms/ImageInput'
import MobileHeader from '../../../../components/Mobile/MobileHeader'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import SectionTitle from '../../../../components/Others/SectionTitle'
import ButtonBase from '../../../../components/FilesView/ButtonBase'
import PageHeader from '../../../../components/Others/PageHeader'
import InfoBox from '../../../../components/Others/Forms/InfoBox'
import {required} from 'vee-validate/dist/rules'
import {mapGetters} from 'vuex'
import {events} from '../../../../bus'
import axios from 'axios'

export default {
	name: 'CreateMeteredPlan',
	components: {
		ValidationProvider,
		ValidationObserver,
		AppInputSwitch,
		SwitchInput,
		SectionTitle,
		AppInputText,
		MobileHeader,
		SelectInput,
		ButtonBase,
		ImageInput,
		PageHeader,
		FormLabel,
		required,
		InfoBox,
	},
	computed: {
		...mapGetters(['currencyList', 'intervalList', 'config']),
	},
	data() {
		return {
			errorMessage: undefined,
			isLoading: false,
			isError: false,
			plan: {
				type: 'fixed',
				name: undefined,
				description: undefined,
				currency: undefined,
				features: {
					bandwidth: {
						active: false,
						per_unit: undefined,
						first_unit: 1,
						aggregate_strategy: 'sum_of_usage',
					},
					storage: {
						active: false,
						per_unit: undefined,
						first_unit: 1,
						aggregate_strategy: 'maximum_usage',
					},
					member: {
						active: false,
						per_unit: undefined,
						first_unit: 1,
						aggregate_strategy: 'maximum_usage',
					},
					flatFee: {
						active: false,
						per_unit: undefined,
						aggregate_strategy: 'maximum_usage',
					},
				},
			},
		}
	},
	methods: {
		async createPlan() {
			let tiers = []

			Object.entries(this.plan.features).forEach(([key, feature]) => {
				if (feature.active) {
					tiers.push({
						aggregate_strategy: feature.aggregate_strategy,
						key: key,
						tiers: [
							{
								per_unit: feature.per_unit,
								first_unit: 1,
								flat_fee: null,
								last_unit: null,
							},
						],
					})
				}
			})

			// Validate fields
			const isValid = await this.$refs.createPlan.validate()

			if (!isValid) return

			// Start loading
			this.isLoading = true

			axios
				.post('/api/subscriptions/admin/plans', {
					type: 'metered',
					name: this.plan.name,
					description: this.plan.description,
					currency: this.plan.currency,
					meters: tiers,
				})
				.then((response) => {
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('toaster.plan_created'),
					})

					// Go to plan page
					this.$router.push({
						name: 'PlanMeteredSettings',
						params: {id: response.data.data.id},
					})

					// Set default state {isEmptyPlans} to false
					if (this.config.isEmptyPlans) {
						this.$store.commit('REPLACE_CONFIG_VALUE', {
							key: 'isEmptyPlans',
							value: false,
						})
					}
				})
				.catch((error) => {
					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('popup_error.title'),
					})
				})
				.finally(() => {
					this.isLoading = false
				})
		},
	},
}
</script>
