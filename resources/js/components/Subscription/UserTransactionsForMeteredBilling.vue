<template>
	<div class="card shadow-card">
		<FormLabel icon="file-text">
			{{ $t('Transactions') }}
		</FormLabel>

		<DatatableWrapper
			class="overflow-x-auto"
			api="/api/user/transactions"
			:paginator="true"
			:columns="columns"
		>
			<!--TODO: refactor all tables as this template-->
			<template slot-scope="{ row }">
				<tr class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
					<td class="py-5 md:pr-1 pr-3">
						<span class="text-sm font-bold">
							{{ row.data.attributes.note }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<ColorLabel class="capitalize" :color="$getTransactionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
						</ColorLabel>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold capitalize">
							{{ $t(row.data.attributes.type) }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
							{{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold">
							{{ row.data.attributes.created_at }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<div class="w-28">
							<img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
						</div>
					</td>
					<td class="md:pl-1 pl-3 text-right">
						<div v-if="row.data.attributes.metadata" class="flex space-x-2 w-full justify-end">
							<div @click="showTransactionDetail(row.data.id)" class="cursor-pointer flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors">
								<EyeIcon size="15" class="opacity-75" />
							</div>
							<a :href="$getInvoiceLink(row.data.id)" target="_blank" class="cursor-pointer flex items-center justify-center w-8 h-8 rounded-md hover:bg-purple-100 dark:bg-2x-dark-foreground bg-light-background transition-colors">
								<FileTextIcon size="15" class="opacity-75" />
							</a>
						</div>
						<div v-else>
							-
						</div>
					</td>
				</tr>

				<!--Transaction detail-->
				<tr v-if="row.data.attributes.metadata && showedTransactionDetailById === row.data.id">
					<td colspan="7" class="rounded-lg overflow-hidden py-2">
						<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in row.data.attributes.metadata" :key="i">
							<div class="w-2/4 leading-none">
								<b class="text-sm font-bold leading-none">
									{{ $t(usage.feature) }}
								</b>
								<small class="text-xs text-gray-500 pt-2 leading-none sm:block hidden">
									{{ $t(`feature_usage_desc_${usage.feature}`) }}
								</small>
							</div>
							<div class="text-left w-1/4">
								<span class="text-sm font-bold text-gray-560">
									{{ usage.usage }}
								</span>
							</div>
							<div class="text-right w-1/4">
								<span class="text-sm font-bold text-theme">
									{{ usage.cost }}
								</span>
							</div>
						</div>
					</td>
				</tr>
			</template>

			<!--Empty page-->
			<template v-slot:empty-page>
				<InfoBox style="margin-bottom: 0">
					<p>{{ $t('user_invoices.empty') }}</p>
				</InfoBox>
			</template>
		</DatatableWrapper>
	</div>
</template>
<script>
	import {EyeIcon, FileTextIcon} from 'vue-feather-icons'
	import ColorLabel from "../Others/ColorLabel"
	import DatatableWrapper from "../Others/Tables/DatatableWrapper"
	import FormLabel from "../Others/Forms/FormLabel"
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {mapGetters} from "vuex";

	export default {
		name: 'UserTransactionsForMeteredBilling',
		components: {
			DatatableWrapper,
			ColorLabel,
			FormLabel,
			InfoBox,
			FileTextIcon,
			EyeIcon,
		},
		computed: {
			...mapGetters([
				'user',
			])
		},
		data() {
			return {
				showedTransactionDetailById: undefined,
				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('Status'),
						field: 'status',
						sortable: true
					},
					{
						label: this.$t('Type'),
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
					{
						label: this.$t('Actions'),
						sortable: false
					},
				],
			}
		},
		methods: {
			showTransactionDetail(id) {
				if (this.showedTransactionDetailById === id)
					this.showedTransactionDetailById = undefined
				else
					this.showedTransactionDetailById = id
			}
		}
	}
</script>