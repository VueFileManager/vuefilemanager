<template>
	<DatatableWrapper
		api="/api/admin/dashboard/transactions"
		:columns="columns"
		class="overflow-x-auto"
	>
		<template slot-scope="{ row }">
			<tr class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
				<td class="py-5 md:pr-1 pr-3">
					<span class="text-sm font-bold">
						{{ row.data.attributes.note }}
					</span>
				</td>
				<td class="md:px-1 px-3">
					<div v-if="row.data.relationships.user" class="flex items-center">
						<MemberAvatar
							:is-border="false"
							:size="36"
							:member="row.data.relationships.user"
						/>
						<div class="ml-3 pr-10">
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
				<td class="md:px-1 px-3">
					<ColorLabel v-if="config.subscriptionType === 'fixed'" :color="$getTransactionStatusColor(row.data.attributes.status)">
						{{ row.data.attributes.status }}
					</ColorLabel>
					<ColorLabel v-if="config.subscriptionType === 'metered'" :color="$getTransactionTypeColor(row.data.attributes.type)">
						{{ row.data.attributes.type }}
					</ColorLabel>
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
				<td class="md:pl-1 pl-3 text-right">
					<img class="inline-block h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
				</td>
			</tr>
		</template>

		<!--Empty page-->
		<template v-slot:empty-page>
			<InfoBox style="margin-bottom: 0">
				<p>{{ $t("There aren't any transactions.") }}</p>
			</InfoBox>
		</template>
	</DatatableWrapper>
</template>

<script>
    import DatatableCellImage from '/resources/js/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons"
	import MemberAvatar from "../FilesView/MemberAvatar"
	import InfoBox from "../Others/Forms/InfoBox"
	import { mapGetters } from 'vuex'

    export default {
        name: 'WidgetLatestTransactions',
        props: [
			'icon',
			'title'
		],
        components: {
            DatatableCellImage,
            DatatableWrapper,
			MemberAvatar,
            Trash2Icon,
            ColorLabel,
            Edit2Icon,
			InfoBox,
        },
		computed: {
			...mapGetters([
				'config',
			]),
		},
		data() {
			return {
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
