<template>
    <DatatableWrapper api="/api/admin/dashboard/transactions" :columns="columns" class="overflow-x-auto">
        <template slot-scope="{ row }">
            <tr class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5">
                <td class="py-5 pr-3 md:pr-1">
                    <span class="text-sm font-bold">
                        {{ row.data.attributes.note }}
                    </span>
                </td>
                <td class="px-3 md:px-1">
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
                    <ColorLabel
                        v-if="config.subscriptionType === 'fixed'"
                        :color="$getTransactionStatusColor(row.data.attributes.status)"
                    >
                        {{ $t(row.data.attributes.status) }}
                    </ColorLabel>
                    <ColorLabel
                        v-if="config.subscriptionType === 'metered'"
                        :color="$getTransactionTypeColor(row.data.attributes.type)"
                    >
                        {{ $t(row.data.attributes.type) }}
                    </ColorLabel>
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
                <td class="pl-3 md:pl-1">
                    <div class="w-28">
                        <img
                            class="inline-block max-h-5"
                            :src="$getPaymentLogo(row.data.attributes.driver)"
                            :alt="row.data.attributes.driver"
                        />
                    </div>
                </td>
            </tr>
        </template>

        <!--Empty page-->
        <template v-slot:empty-page>
            <InfoBox style="margin-bottom: 0">
                <p>{{ $t("not_any_transactions") }}</p>
            </InfoBox>
        </template>
    </DatatableWrapper>
</template>

<script>
import DatatableCellImage from '../../UI/Table/DatatableCellImage'
import DatatableWrapper from '../../UI/Table/DatatableWrapper'
import ColorLabel from '../../UI/Labels/ColorLabel'
import { Trash2Icon, Edit2Icon } from 'vue-feather-icons'
import MemberAvatar from '../../UI/Others/MemberAvatar'
import InfoBox from '../../UI/Others/InfoBox'
import { mapGetters } from 'vuex'

export default {
    name: 'WidgetLatestTransactions',
    props: ['icon', 'title'],
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
        ...mapGetters(['config']),
    },
    data() {
        return {
            columns: [
                {
                    label: this.$t('note'),
                    field: 'note',
                    sortable: true,
                },
                {
                    label: this.$t('user'),
                    field: 'user_id',
                    sortable: true,
                },
                {
                    label: this.$t('status'),
                    field: 'status',
                    sortable: true,
                },
                {
                    label: this.$t('total'),
                    field: 'amount',
                    sortable: true,
                },
                {
                    label: this.$t('payed_at'),
                    field: 'created_at',
                    sortable: true,
                },
                {
                    label: this.$t('service'),
                    field: 'driver',
                    sortable: true,
                },
            ],
        }
    },
}
</script>
