<template>
	<ValidationObserver @submit.prevent="createPlan" ref="createPlan" v-slot="{ invalid }" tag="form">

		<div class="card shadow-card">
			<FormLabel>
				{{ $t('Details') }}
			</FormLabel>

			<!--Name-->
			<ValidationProvider tag="div" mode="passive" name="Name" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('admin_page_plans.form.name')">
					<input v-model="plan.name" :placeholder="$t('admin_page_plans.form.name_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
				</AppInputText>
			</ValidationProvider>

			<!--Description-->
			<ValidationProvider tag="div" mode="passive" name="Description" v-slot="{ errors }">
				<AppInputText :title="$t('admin_page_plans.form.description')" :is-last="true">
					<textarea v-model="plan.description" :placeholder="$t('admin_page_plans.form.description_plac')" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark"></textarea>
				</AppInputText>
			</ValidationProvider>
		</div>

		<div class="card shadow-card">
			<FormLabel>
				{{ $t('Pricing') }}
			</FormLabel>

			<div class="flex space-x-4">
				<!--Price-->
				<ValidationProvider tag="div" mode="passive" name="Price" rules="required" v-slot="{ errors }" class="w-full">
					<AppInputText :title="$t('admin_page_plans.form.price')" class="w-full">
						<input v-model="plan.amount" :placeholder="$t('admin_page_plans.form.price_plac')" type="number" step="0.01" min="1" max="999999999999" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>

				<!--Currency-->
				<ValidationProvider tag="div" mode="passive" name="Currency" rules="required" v-slot="{ errors }" class="w-full">
					<AppInputText :title="$t('Currency')" class="w-full">
						<SelectInput v-model="plan.currency" :options="currencyList" :placeholder="$t('Select plan currency')" :isError="errors[0]" />
					</AppInputText>
				</ValidationProvider>
			</div>

			<!--Interval-->
			<ValidationProvider tag="div" mode="passive" name="Interval" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('Interval')" :is-last="true">
					<SelectInput v-model="plan.interval" :options="intervalList" :placeholder="$t('Select billing interval')" :isError="errors[0]" />
				</AppInputText>
			</ValidationProvider>
		</div>

		<div class="card shadow-card">
			<FormLabel>
				{{ $t('Features') }}
			</FormLabel>

			<!--Storage Capacity-->
			<ValidationProvider tag="div" mode="passive" name="Max Storage Capacity" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('admin_page_plans.form.storage')" :description="$t('admin_page_plans.form.storage_helper')">
					<input v-model="plan.features.max_storage_amount" :placeholder="$t('admin_page_plans.form.storage_plac')" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
				</AppInputText>
			</ValidationProvider>

			<!--Team Members-->
			<ValidationProvider tag="div" mode="passive" name="Max Team Members" rules="required" v-slot="{ errors }">
				<AppInputText :title="$t('Team Members')" :description="$t('To set unlimited team members, type -1 into form')" :is-last="true">
					<input v-model="plan.features.max_team_members" :placeholder="$t('Add max team members in number')" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
				</AppInputText>
			</ValidationProvider>
		</div>

		<InfoBox v-if="isError" type="error" style="margin-top: 40px">
			<p>{{ errorMessage }}</p>
		</InfoBox>

		<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
			{{ $t('admin_page_plans.create_plan_button') }}
		</ButtonBase>
	</ValidationObserver>
</template>

<script>
	import AppInputText from "../../../components/Admin/AppInputText";
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
	import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import SectionTitle from '/resources/js/components/Others/SectionTitle'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageHeader from '/resources/js/components/Others/PageHeader'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import axios from 'axios'

	export default {
		name: 'PlanCreate',
		components: {
			ValidationProvider,
			ValidationObserver,
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
			...mapGetters([
				'currencyList',
				'intervalList',
			])
		},
		data() {
			return {
				errorMessage: undefined,
				isLoading: false,
				isError: false,
				plan: {
					name: undefined,
					description: undefined,
					interval: undefined,
					amount: undefined,
					currency: undefined,
					features: {
						max_storage_amount: undefined,
						max_team_members: undefined,
					},
				}
			}
		},
		methods: {
			async createPlan() {

				// Validate fields
				const isValid = await this.$refs.createPlan.validate();

				if (!isValid) return;

				// Start loading
				this.isLoading = true

				axios
					.post('/api/subscriptions/plans', this.plan)
					.then(response => {

						// Show toaster
						events.$emit('toaster', {
							type: 'success',
							message: this.$t('toaster.plan_created'),
						})

						// Go to User page
						this.$router.push({name: 'PlanSettings', params: {id: response.data.data.id}})
					})
					.catch(error => {

						// Validation errors
						if (error.response.status === 422) {

							if (error.response.data.errors['max_storage_amount']) {
								this.$refs.createPlan.setErrors({
									'Max Storage Capacity': this.$t('errors.capacity_digit')
								});
							}
						}

						if (error.response.status === 500) {
							this.isError = true
							this.errorMessage = error.response.data.message
						}

					})
					.finally(() => {
						this.isLoading = false
					})
			}
		},
	}
</script>
