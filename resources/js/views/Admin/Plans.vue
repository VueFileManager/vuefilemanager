<template>
    <div>
        <!--Plans-->
        <div v-if="!config.isEmptyPlans" class="card shadow-card">
            <!--Create button-->
            <div v-if="!config.isCreatedMeteredPlan || config.subscriptionType === 'fixed'" class="mb-6">
                <router-link :to="{ name: createPlanRoute }">
                    <MobileActionButton icon="plus">
                        {{ $t('create_plan') }}
                    </MobileActionButton>
                </router-link>
				<MobileActionButton @click.native="synchronizePlans" icon="refresh">
					{{ $t('synchronize_plans') }}
				</MobileActionButton>
            </div>

            <!--Datatable-->
            <DatatableWrapper
                @data="plans = $event"
                @init="isLoading = false"
                api="/api/subscriptions/admin/plans"
                :paginator="true"
                :columns="columns"
                class="overflow-x-auto"
            >
                <template slot-scope="{ row }">
                    <!--Metered subscription-->
                    <tr
                        v-if="config.subscriptionType === 'metered'"
                        class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5"
                    >
                        <td class="py-5 pr-3 md:pr-1">
                            <router-link
                                class="text-sm font-bold"
                                :to="{
                                    name: 'PlanMeteredSettings',
                                    params: { id: row.data.id },
                                }"
                            >
                                {{ row.data.attributes.name }}
                            </router-link>
                        </td>
                        <td class="px-3 md:px-1">
                            <ColorLabel :color="$getPlanStatusColor(row.data.attributes.status)">
                                {{ $t(row.data.attributes.status) }}
                            </ColorLabel>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.currency }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold capitalize">
                                {{ $t(row.data.attributes.interval) }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td class="pl-3 text-right md:pl-1">
                            <div class="flex w-full justify-end space-x-2">
                                <router-link
                                    :to="{
                                        name: 'PlanMeteredSettings',
                                        params: { id: row.data.id },
                                    }"
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-green-100 dark:bg-2x-dark-foreground"
                                >
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link
                                    v-if="row.data.attributes.status !== 'archived'"
                                    :to="{
                                        name: 'PlanMeteredDelete',
                                        params: { id: row.data.id },
                                    }"
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-red-100 dark:bg-2x-dark-foreground"
                                >
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>

                    <!--Fixed subscription-->
                    <tr
                        v-if="config.subscriptionType === 'fixed'"
                        class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5"
                    >
                        <td class="py-5 pr-3 md:pr-1">
							<span v-if="row.data.attributes.status === 'archived'" class="ml-6 text-gray-300">-</span>
                            <SwitchInput
								v-if="row.data.attributes.status === 'active'"
                                @input="
                                    $updateInput(
                                        `/subscriptions/admin/plans/${row.data.id}`,
                                        'visible',
                                        row.data.attributes.visible
                                    )
                                "
                                v-model="row.data.attributes.visible"
                                :state="row.data.attributes.visible"
                                class="switch"
                            />
                        </td>
                        <td class="px-3 md:px-1">
                            <router-link
                                class="text-sm font-bold"
                                :to="{
                                    name: 'PlanFixedSettings',
                                    params: { id: row.data.id },
                                }"
                            >
                                {{ row.data.attributes.name }}
                            </router-link>
                        </td>
						<td class="px-3 md:px-1">
                            <ColorLabel :color="$getPlanStatusColor(row.data.attributes.status)">
                                {{ $t(row.data.attributes.status) }}
                            </ColorLabel>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.price }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold capitalize">
                                {{ $t(row.data.attributes.interval) }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.subscribers }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.features.max_storage_amount }}
                                GB
                            </span>
                        </td>
                        <td class="pl-3 text-right md:pl-1">
                            <div class="flex w-full justify-end space-x-2">
                                <router-link
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-green-100 dark:bg-2x-dark-foreground"
                                    :to="{
                                        name: 'PlanFixedSettings',
                                        params: { id: row.data.id },
                                    }"
                                >
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-red-100 dark:bg-2x-dark-foreground"
                                    :to="{
                                        name: 'PlanFixedDelete',
                                        params: { id: row.data.id },
                                    }"
                                >
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </div>

        <!--Empty State-->
        <div v-if="config.isEmptyPlans" class="flex items-center justify-center fixed top-0 bottom-0 left-0 right-0">
            <div class="text-center">
                <img
                    class="mb-6 inline-block w-28"
                    src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f9fe.svg"
                    alt="transaction"
                />

                <h1 class="mb-1 text-2xl font-bold">
                    {{ $t('there_is_nothing') }}
                </h1>

                <p class="text-sm text-gray-600">
                    {{ $t('all_visible_plans_here') }}
                </p>

                <router-link :to="{ name: createPlanRoute }" class="mt-6 inline-block">
                    <ButtonBase class="action-confirm" button-style="theme">
                        {{ $t('create_first_plan') }}
                    </ButtonBase>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import DatatableWrapper from '../../components/UI/Table/DatatableWrapper'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton'
import SwitchInput from '../../components/Inputs/SwitchInput'
import ButtonBase from '../../components/UI/Buttons/ButtonBase'
import ColorLabel from '../../components/UI/Labels/ColorLabel'
import { Trash2Icon, Edit2Icon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import {events} from "../../bus";

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
        ...mapGetters(['config']),
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
                        label: this.$t('name'),
                        field: 'name',
                        sortable: true,
                    },
                    {
                        label: this.$t('status'),
                        field: 'status',
                        sortable: true,
                    },
                    {
                        label: this.$t('currency'),
                        field: 'currency',
                        sortable: true,
                    },
                    {
                        label: this.$t('interval'),
                        field: 'interval',
                        sortable: true,
                    },
                    {
                        label: this.$t('subscribers'),
                        sortable: false,
                    },
                    {
                        label: this.$t('action'),
                        sortable: false,
                    },
                ],
                fixed: [
                    {
                        label: this.$t('visibility'),
                        field: 'visible',
                        sortable: true,
                    },
                    {
                        label: this.$t('name'),
                        field: 'name',
                        sortable: true,
                    },
					{
						label: this.$t('status'),
						field: 'status',
						sortable: true,
					},
                    {
                        label: this.$t('price'),
                        field: 'amount',
                        sortable: true,
                    },
                    {
                        label: this.$t('interval'),
                        field: 'interval',
                        sortable: true,
                    },
                    {
                        label: this.$t('subscribers'),
                        sortable: false,
                    },
                    {
                        label: this.$t('storage'),
                        sortable: false,
                    },
                    {
                        label: this.$t('action'),
                        sortable: false,
                    },
                ],
            }[this.config.subscriptionType]
        },
    },
	methods: {
		synchronizePlans() {
			let processingPopup = setTimeout(() => {
				this.$store.commit('PROCESSING_POPUP', {
					title: this.$t('synchronizing_plans'),
					message: this.$t('plans_are_synchronizing'),
				})
			}, 300)

			axios.get('/api/subscriptions/admin/plans/synchronize')
				.then(() => {
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('plans_was_synchronized'),
					})
				})
				.catch((error) => {
					if (error.response.status === 500 && error.response.data.type) {
						events.$emit('alert:open', {
							title: error.response.data.title,
							message: error.response.data.message,
						})
					}
				})
				.finally(() => {
					clearTimeout(processingPopup)

					this.$store.commit('PROCESSING_POPUP', undefined)
				})
		}
	}
}
</script>
