<template>
    <div>
        <!--Datatable-->
        <DatatableWrapper
            v-if="!config.isEmptySubscriptions"
            @init="isLoading = false"
            api="/api/subscriptions/admin"
            :paginator="true"
            :columns="columns"
            class="card overflow-x-auto shadow-card"
        >
            <!--Table data content-->
            <template slot-scope="{ row }">
                <tr class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5">
                    <td class="py-5 pr-3 md:pr-1">
                        <router-link
							v-if="row.data.relationships.user"
                            class="flex items-center"
                            :to="{
                                name: 'UserDetail',
                                params: {
                                    id: row.data.relationships.user.data.id,
                                },
                            }"
                        >
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
                        </router-link>
						<span v-else class="text-sm font-bold">
                            -
                        </span>
                    </td>
                    <td class="px-3 md:px-1">
                        <ColorLabel :color="$getSubscriptionStatusColor(row.data.attributes.status)">
                            {{ $t(row.data.attributes.status) }}
                        </ColorLabel>
                    </td>
                    <td class="px-3 md:px-1">
                        <span class="text-limit text-sm font-bold capitalize" style="max-width: 160px">
                            {{ row.data.attributes.name }}
                        </span>
                        <span class="block text-xs font-bold text-gray-400">
                            {{ row.data.relationships.plan.data.attributes.price }}
                            /
                            {{ $t(`interval.${row.data.relationships.plan.data.attributes.interval}`) }}
                        </span>
                    </td>
                    <td class="px-3 md:px-1">
                        <span class="text-sm font-bold">
                            {{ row.data.attributes.renews_at }}
                        </span>
                    </td>
                    <td class="px-3 md:px-1">
                        <span class="text-sm font-bold">
                            {{ row.data.attributes.ends_at ? row.data.attributes.ends_at : '-' }}
                        </span>
                    </td>
                    <td class="pl-3 text-right md:pl-1">
                        <img
                            class="inline-block max-h-5"
                            :src="$getPaymentLogo(row.data.attributes.driver)"
                            :alt="row.data.attributes.driver"
                        />
                    </td>
                </tr>
            </template>
        </DatatableWrapper>

        <!--Empty State-->
        <div v-if="config.isEmptySubscriptions" class="flex items-center justify-center fixed top-0 bottom-0 left-0 right-0">
            <div class="text-center">
                <img
                    class="mb-6 inline-block w-28"
                    src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f5c3.svg"
                    alt="transaction"
                />

                <h1 class="mb-1 text-2xl font-bold">
                    {{ $t('there_is_nothing') }}
                </h1>

                <p class="text-sm text-gray-600">
                    {{ $t('all_visible_subscriptions_here') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import ColorLabel from '../../components/UI/Labels/ColorLabel'
import MemberAvatar from '../../components/UI/Others/MemberAvatar'
import DatatableWrapper from '../../components/UI/Table/DatatableWrapper'
import { mapGetters } from 'vuex'

export default {
    name: 'Subscriptions',
    components: {
        ColorLabel,
        MemberAvatar,
        DatatableWrapper,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            isLoading: true,
            columns: [
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
                    label: this.$t('note'),
                    field: 'plan.name',
                    sortable: true,
                },
                {
                    label: this.$t('renews_at'),
                    field: 'created_at',
                    sortable: true,
                },
                {
                    label: this.$t('ends_at'),
                    field: 'created_at',
                    sortable: true,
                },
                {
                    label: this.$t('service'),
                    field: 'driver.driver',
                    sortable: true,
                },
            ],
        }
    },
}
</script>
