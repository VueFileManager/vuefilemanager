<template>
    <div>
		<!--Datatable-->
		<DatatableWrapper
			v-if="! config.isEmptyTransactions" class="card shadow-card"
			@init="isLoading = false"
			api="/api/subscriptions/admin/transactions"
			:paginator="true"
			:columns="columns"
		>
			<template slot-scope="{ row }">
				<tr class="border-b dark:border-opacity-5 border-light border-dashed">
					<td class="py-5">
						<span class="text-sm font-bold">
							{{ row.data.attributes.note }}
						</span>
					</td>
					<td>
						<div v-if="row.data.relationships.user" class="flex items-center">
							<MemberAvatar
								:is-border="false"
								:size="36"
								:member="row.data.relationships.user"
							/>
							<div class="ml-3">
								<b class="text-sm font-bold block max-w-1 overflow-hidden overflow-ellipsis whitespace-nowrap" style="max-width: 155px;">
									{{ row.data.relationships.user.data.attributes.name }}
								</b>
								<span class="block text-xs dark:text-gray-500 text-gray-600">
									{{ row.data.relationships.user.data.attributes.email }}
								</span>
							</div>
						</div>
						<span v-if="! row.data.relationships.user" class="text-xs text-gray-500 font-bold">
							{{ $t('User was deleted') }}
						</span>
					</td>
					<td>
						<ColorLabel v-if="config.subscriptionType === 'fixed'" :color="$getTransactionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
						</ColorLabel>
						<ColorLabel v-if="config.subscriptionType === 'metered'" :color="$getTransactionTypeColor(row.data.attributes.type)">
							{{ row.data.attributes.type }}
						</ColorLabel>
					</td>
					<td>
						<span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
							{{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
						</span>
					</td>
					<td>
						<span class="text-sm font-bold">
							{{ row.data.attributes.created_at }}
						</span>
					</td>
					<td class="text-right">
						<img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
					</td>
				</tr>
			</template>
		</DatatableWrapper>

		<!--Empty State-->
        <div class="flex items-center justify-center h-full">
			<div class="text-center">
				<img class="w-28 inline-block mb-6" src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f9ee.svg" alt="transaction">
				<h1 class="text-2xl font-bold mb-1">
					{{ $t("There is Nothing") }}
				</h1>
				<p class="text-sm text-gray-600">
					{{ $t('All your transactions will be visible here') }}
				</p>
			</div>
		</div>
    </div>
</template>

<script>
	import MemberAvatar from "../../components/FilesView/MemberAvatar"
	import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
	import ColorLabel from '/resources/js/components/Others/ColorLabel'
	import {mapGetters} from 'vuex'

	export default {
		name: 'Invoices',
		components: {
			DatatableWrapper,
			MemberAvatar,
			ColorLabel,
		},
		computed: {
			...mapGetters([
				'config',
			]),
		},
		data() {
			return {
				isLoading: true,
				invoices: [],
				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('User'),
						field: 'user_id',
						sortable: true
					},
					{
						label: this.$t('Status'),
						field: 'status',
						sortable: true
					},
					{
						label: this.$t('admin_page_invoices.table.total'),
						field: 'amount',
						sortable: true
					},
					{
						label: this.$t('Payed At'),
						field: 'created_at',
						sortable: true
					},
					{
						label: this.$t('Service'),
						field: 'driver',
						sortable: true
					},
				],
			}
		},
	}
</script>
