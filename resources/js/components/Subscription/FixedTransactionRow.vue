<template>
	<tr class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
		<td class="py-5 md:pr-1 pr-3">
			<span class="text-sm font-bold">
				{{ row.data.attributes.note }}
			</span>
		</td>
		<td v-if="user" class="md:px-1 px-3 whitespace-nowrap">
			<div v-if="row.data.relationships.user" class="flex items-center">
				<MemberAvatar
					:is-border="false"
					:size="36"
					:member="row.data.relationships.user"
				/>
				<div class="ml-3 pr-10">
					<b class="text-sm font-bold block max-w-1 overflow-hidden text-ellipsis whitespace-nowrap" style="max-width: 155px;">
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
			<ColorLabel class="capitalize" :color="$getTransactionStatusColor(row.data.attributes.status)">
				{{ row.data.attributes.status }}
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
		<td class="md:px-1 px-3">
			<div class="w-28">
				<img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
			</div>
		</td>
		<td class="md:pl-1 pl-3 text-right">
			<div class="inline-block">
				<a :href="$getInvoiceLink(row.data.id)" target="_blank" class="inline-block cursor-pointer flex items-center justify-center w-8 h-8 rounded-md hover:bg-purple-100 dark:bg-2x-dark-foreground bg-light-background transition-colors">
					<FileTextIcon size="15" class="opacity-75" />
				</a>
			</div>
		</td>
	</tr>
</template>
<script>
	import MemberAvatar from "../FilesView/MemberAvatar";
	import MeteredTransactionDetailRow from "./MeteredTransactionDetailRow";
	import ColorLabel from "../Others/ColorLabel";
	import {EyeIcon, FileTextIcon} from 'vue-feather-icons'

	export default {
		name: 'FixedTransactionRow',
		components: {
			MeteredTransactionDetailRow,
			MemberAvatar,
			FileTextIcon,
			ColorLabel,
			EyeIcon,
		},
		props: {
			row: {},
			user: {
				type: Boolean,
				default: false
			},
		},
		data() {
			return {
				showedTransactionDetailById: undefined,
			}
		},
		methods: {
			showTransactionDetail(id) {
				if (this.showedTransactionDetailById === id)
					this.showedTransactionDetailById = undefined
				else
					this.showedTransactionDetailById = id
			}
		},
	}
</script>