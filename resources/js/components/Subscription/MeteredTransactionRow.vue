<template>
    <tr class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5">
        <td class="py-5 pr-3 md:pr-1">
            <span class="text-sm font-bold">
                {{ row.data.attributes.note }}
            </span>
        </td>
        <td v-if="user" class="whitespace-nowrap px-3 md:px-1">
            <div v-if="row.data.relationships.user" class="flex items-center">
                <MemberAvatar :is-border="false" :size="36" :member="row.data.relationships.user" />
                <div class="ml-3 pr-10">
                    <b
                        class="max-w-1 block overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                        style="max-width: 155px"
                    >
                        {{ row.data.relationships.user.data.attributes.name }}
                    </b>
                    <span class="block text-xs text-gray-600 dark:text-gray-500">
                        {{ row.data.relationships.user.data.attributes.email }}
                    </span>
                </div>
            </div>
            <span v-if="!row.data.relationships.user" class="text-xs font-bold text-gray-500">
                {{ $t('user_was_deleted') }}
            </span>
        </td>
        <td class="px-3 md:px-1">
            <ColorLabel class="capitalize" :color="$getTransactionStatusColor(row.data.attributes.status)">
                {{ $t(row.data.attributes.status) }}
            </ColorLabel>
        </td>
        <td class="px-3 md:px-1">
            <span class="text-sm font-bold capitalize">
                {{ $t(row.data.attributes.type) }}
            </span>
        </td>
        <td class="px-3 md:px-1">
            <span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
                {{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
            </span>
        </td>
        <td class="px-3 md:px-1">
            <span class="text-sm font-bold">
                {{ row.data.attributes.created_at }}
            </span>
        </td>
        <td class="px-3 md:px-1">
            <div class="w-28">
                <img
                    class="inline-block max-h-5"
                    :src="$getPaymentLogo(row.data.attributes.driver)"
                    :alt="row.data.attributes.driver"
                />
            </div>
        </td>
        <td class="pl-3 text-right md:pl-1">
            <div v-if="row.data.attributes.metadata" class="flex w-full justify-end space-x-2">
                <div
                    @click="$emit('showDetail', row.data.id)"
                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-md bg-light-background transition-colors hover:bg-green-100 dark:bg-2x-dark-foreground"
                >
                    <EyeIcon size="15" class="opacity-75" />
                </div>
                <a
                    :href="$getInvoiceLink(row.data.id)"
                    target="_blank"
                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-md bg-light-background transition-colors hover:bg-purple-100 dark:bg-2x-dark-foreground"
                >
                    <FileTextIcon size="15" class="opacity-75" />
                </a>
            </div>
            <div v-else>-</div>
        </td>
    </tr>
</template>
<script>
import MemberAvatar from '../UI/Others/MemberAvatar'
import MeteredTransactionDetailRow from './MeteredTransactionDetailRow'
import ColorLabel from '../UI/Labels/ColorLabel'
import { EyeIcon, FileTextIcon } from 'vue-feather-icons'

export default {
    name: 'MeteredTransactionRow',
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
            default: false,
        },
    },
    data() {
        return {
            showedTransactionDetailById: undefined,
        }
    },
    methods: {
        showTransactionDetail(id) {
            if (this.showedTransactionDetailById === id) this.showedTransactionDetailById = undefined
            else this.showedTransactionDetailById = id
        },
    },
}
</script>
