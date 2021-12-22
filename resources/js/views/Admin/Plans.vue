<template>
    <div>
		<div class="card shadow-card">
			<div class="mb-6">
				<router-link :to="{name: createPlanRoute}">
					<MobileActionButton icon="plus">
						{{ $t('admin_page_plans.create_plan_button') }}
					</MobileActionButton>
				</router-link>
			</div>

			<!--Datatable-->
            <DatatableWrapper @data="plans = $event" @init="isLoading = false" api="/api/subscriptions/admin/plans" :paginator="true" :columns="columns">
                <template slot-scope="{ row }">

					<!--Metered subscription-->
                    <tr v-if="config.subscriptionType === 'metered'" class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td>
							<router-link class="text-sm font-bold" :to="{name: 'PlanMeteredSettings', params: {id: row.data.id}}">
                            	{{ row.data.attributes.name }}
							</router-link>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.currency }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold capitalize">
                            	{{ row.data.attributes.interval }}
                            </span>
                        </td>
						<td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2 w-full justify-end">
                                <router-link
									:to="{name: 'PlanMeteredSettings', params: {id: row.data.id}}"
									class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors"
								>
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link
									:to="{name: 'PlanMeteredDelete', params: {id: row.data.id}}"
									class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-100 dark:bg-2x-dark-foreground bg-light-background transition-colors"
								>
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>

					<!--Fixed subscription-->
                    <tr v-if="config.subscriptionType === 'fixed'" class="border-b dark:border-opacity-5 border-light border-dashed">
						<td class="py-4">
							<SwitchInput @input="$updateInput(`/subscriptions/admin/plans/${row.data.id}`, 'visible', row.data.attributes.visible)" v-model="row.data.attributes.visible" :state="row.data.attributes.visible" class="switch"/>
						</td>
                        <td>
							<router-link class="text-sm font-bold" :to="{name: 'PlanFixedSettings', params: {id: row.data.id}}">
                            	{{ row.data.attributes.name }}
							</router-link>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.price }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold capitalize">
                            	{{ row.data.attributes.interval }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.features.max_storage_amount }} GB
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2 w-full justify-end">
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'PlanSettings', params: {id: row.data.id}}">
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'PlanDelete', params: {id: row.data.id}}">
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
		</div>

        <!--Stripe configured but has empty plans-->
		<!--<EmptyPageContent
                v-if="isEmptyPlans"
                icon="file"
                :title="$t('admin_page_plans.empty.title')"
                :description="$t('admin_page_plans.empty.description')"
        >
            <router-link :to="{name: 'CreateFixedPlan'}" tag="p">
                <ButtonBase button-style="theme">{{ $t('admin_page_plans.empty.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>-->

        <!--Stripe is Not Configured-->
		<!--<EmptyPageContent
                v-if="stripeIsNotConfigured"
                icon="settings"
                :title="$t('activation.stripe.title')"
                :description="$t('activation.stripe.description')"
        >
            <router-link :to="{name: 'AppPayments'}">
                <ButtonBase button-style="theme">{{ $t('activation.stripe.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>-->

        <!--Spinner-->
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import EmptyPageContent from '/resources/js/components/Others/EmptyPageContent'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Plans',
        components: {
            MobileActionButton,
            EmptyPageContent,
            DatatableWrapper,
            SwitchInput,
            Trash2Icon,
            ButtonBase,
            Edit2Icon,
            Spinner,
        },
        computed: {
            ...mapGetters([
				'config'
			]),
            isEmptyPlans() {
                return ! this.isLoading && this.plans.length === 0 && this.config.stripe_public_key
            },
			createPlanRoute() {
				return {
					metered: 'CreateMeteredPlan',
					fixed: 'CreateFixedPlan',
				}[this.config.subscriptionType]
			},
			columns() {
				return {
					metered: [
						{
							label: this.$t('Name'),
							field: 'name',
							sortable: true
						},
						{
							label: this.$t('Currency'),
							field: 'currency',
							sortable: true
						},
						{
							label: this.$t('Interval'),
							field: 'interval',
							sortable: true
						},
						{
							label: this.$t('admin_page_plans.table.subscribers'),
							sortable: false
						},
						{
							label: this.$t('admin_page_user.table.action'),
							sortable: false
						},
					],
					fixed: [
						{
							label: this.$t('Visibility'),
							field: 'visible',
							sortable: true
						},
						{
							label: this.$t('Name'),
							field: 'name',
							sortable: true
						},
						{
							label: this.$t('Price'),
							field: 'amount',
							sortable: true
						},
						{
							label: this.$t('Interval'),
							field: 'interval',
							sortable: true
						},
						{
							label: this.$t('admin_page_plans.table.subscribers'),
							sortable: false
						},
						{
							label: this.$t('Storage'),
							sortable: false
						},
						{
							label: this.$t('admin_page_user.table.action'),
							sortable: false
						},
					],
				}[this.config.subscriptionType]
			}
        },
        data() {
            return {
                isLoading: true,
                plans: [],
            }
        },
        created() {
            if (! this.config.stripe_public_key)
                this.isLoading = false
        }
    }
</script>
