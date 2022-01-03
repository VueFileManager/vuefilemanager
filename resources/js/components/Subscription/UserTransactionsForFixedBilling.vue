<template>
	<div class="card shadow-card">
		<FormLabel icon="file-text">
			{{ $t('Transactions') }}
		</FormLabel>

		<DatatableWrapper
			api="/api/subscriptions/transactions"
			:paginator="true"
			:columns="columns"
		>
			<template slot-scope="{ row }">
				<tr class="border-b dark:border-opacity-5 border-light border-dashed">
					<td class="py-4">
						<span class="text-sm font-bold">
							{{ row.data.attributes.note }}
						</span>
					</td>
					<td>
						<ColorLabel :color="$getTransactionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
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

			<!--Empty page-->
			<template v-slot:empty-page>
				<InfoBox>
					<p>{{ $t('admin_page_user.invoices.empty') }}</p>
				</InfoBox>
			</template>
		</DatatableWrapper>
	</div>
</template>
<script>
import ColorLabel from "../Others/ColorLabel"
import DatatableWrapper from "../Others/Tables/DatatableWrapper"
import FormLabel from "../Others/Forms/FormLabel"
import InfoBox from '/resources/js/components/Others/Forms/InfoBox'

export default {
	name: 'UserTransactionsForFixedBilling',
	components: {ColorLabel, DatatableWrapper, FormLabel, InfoBox},
	data() {
		return {
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
	}
}
</script>