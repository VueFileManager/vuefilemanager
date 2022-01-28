<template>
    <div>
		<!--Plans-->
		<div v-if="! config.isEmptyPlans" class="card shadow-card">

			<!--Create button-->
			<div v-if="! config.isCreatedMeteredPlan || config.subscriptionType === 'fixed'" class="mb-6">
				<router-link :to="{name: createPlanRoute}">
					<MobileActionButton icon="plus">
						{{ $t('admin_page_plans.create_plan_button') }}
					</MobileActionButton>
				</router-link>
			</div>

			<!--Datatable-->
            <DatatableWrapper @data="plans = $event" @init="isLoading = false" api="/api/subscriptions/admin/plans" :paginator="true" :columns="columns" class="overflow-x-auto">
                <template slot-scope="{ row }">

					<!--Metered subscription-->
                    <tr v-if="config.subscriptionType === 'metered'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
                        <td class="py-5 md:pr-1 pr-3">
							<router-link class="text-sm font-bold" :to="{name: 'PlanMeteredSettings', params: {id: row.data.id}}">
                            	{{ row.data.attributes.name }}
							</router-link>
                        </td>
						<td class="md:px-1 px-3">
							<ColorLabel :color="$getPlanStatusColor(row.data.attributes.status)">
								{{ row.data.attributes.status }}
							</ColorLabel>
						</td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.currency }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold capitalize">
                            	{{ row.data.attributes.interval }}
                            </span>
                        </td>
						<td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td class="md:pl-1 pl-3 text-right">
                            <div class="flex space-x-2 w-full justify-end">
                                <router-link
									:to="{name: 'PlanMeteredSettings', params: {id: row.data.id}}"
									class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors"
								>
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link
									v-if="row.data.attributes.status !== 'archived'"
									:to="{name: 'PlanMeteredDelete', params: {id: row.data.id}}"
									class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-100 dark:bg-2x-dark-foreground bg-light-background transition-colors"
								>
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>

					<!--Fixed subscription-->
                    <tr v-if="config.subscriptionType === 'fixed'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
						<td class="py-5 md:pr-1 pr-3">
							<SwitchInput @input="$updateInput(`/subscriptions/admin/plans/${row.data.id}`, 'visible', row.data.attributes.visible)" v-model="row.data.attributes.visible" :state="row.data.attributes.visible" class="switch"/>
						</td>
                        <td class="md:px-1 px-3">
							<router-link class="text-sm font-bold" :to="{name: 'PlanFixedSettings', params: {id: row.data.id}}">
                            	{{ row.data.attributes.name }}
							</router-link>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.price }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold capitalize">
                            	{{ row.data.attributes.interval }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.features.max_storage_amount }} GB
                            </span>
                        </td>
                        <td class="md:pl-1 pl-3 text-right">
                            <div class="flex space-x-2 w-full justify-end">
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'PlanFixedSettings', params: {id: row.data.id}}">
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'PlanFixedDelete', params: {id: row.data.id}}">
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
		</div>

		<!--Empty State-->
        <div v-if="config.isEmptyPlans" class="flex items-center justify-center h-full">
			<div class="text-center">
				<img class="w-28 inline-block mb-6" src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f9fe.svg" alt="transaction">

				<h1 class="text-2xl font-bold mb-1">
					{{ $t("There is Nothing") }}
				</h1>

				<p class="text-sm text-gray-600">
					{{ $t('All your plans will be visible here') }}
				</p>

				<router-link :to="{name: createPlanRoute}" class="inline-block mt-6">
					<ButtonBase class="action-confirm" button-style="theme">
						{{ $t('Create First Plan') }}
					</ButtonBase>
				</router-link>
			</div>
		</div>
    </div>
</template>

<script>
    import DatatableWrapper from "../../components/Others/Tables/DatatableWrapper";
    import MobileActionButton from "../../components/FilesView/MobileActionButton";
    import SwitchInput from "../../components/Others/Forms/SwitchInput";
    import ButtonBase from "../../components/FilesView/ButtonBase";
	import ColorLabel from "../../components/Others/ColorLabel";
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import { mapGetters } from 'vuex'

    export default {
        name: 'Plans',
        components: {
			MobileActionButton,
            DatatableWrapper,
            SwitchInput,
			ColorLabel,
            Trash2Icon,
            ButtonBase,
            Edit2Icon,
        },
        computed: {
            ...mapGetters([
				'config',
			]),
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
							label: this.$t('Status'),
							field: 'status',
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
    }
</script>
