<template>
    <PageTab :is-loading="isLoading">
        <DatatableWrapper
            @data="subscribers = $event"
            @init="isLoading = false"
            :api="`/api/subscriptions/admin/plans/${this.$route.params.id}/subscribers`"
            :paginator="true"
            :columns="columns"
            class="card overflow-x-auto shadow-card"
        >
            <!--Table data content-->
            <template slot-scope="{ row }">
                <tr
                    v-if="config.subscriptionType === 'metered'"
                    class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5"
                >
                    <td class="py-3 pr-3 md:pr-1">
                        <router-link
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
                    </td>
                    <td class="px-3 md:px-1">
                        <span class="whitespace-nowrap text-sm font-bold">
                            {{ row.data.attributes.renews_at }}
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
                <tr
                    v-if="config.subscriptionType === 'fixed'"
                    class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5"
                >
                    <td class="py-3 pr-3 md:pr-1">
                        <router-link
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
                    </td>
                    <td class="px-3 md:px-1">
                        <span class="text-sm font-bold">
                            <!--todo: update renew attribute-->
                            {{
                                row.data.attributes.renews_at
                                    ? row.data.attributes.renews_at
                                    : row.data.attributes.created_at
                            }}
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

            <!--Empty page-->
            <template v-slot:empty-page>
                <InfoBox style="margin-bottom: 0">
                    <p>{{ $t('admin_page_plans.subscribers.empty') }}</p>
                </InfoBox>
            </template>
        </DatatableWrapper>
    </PageTab>
</template>

<script>
import ColorLabel from '../../../../components/UI/Labels/ColorLabel'
import MemberAvatar from '../../../../components/UI/Others/MemberAvatar'
import DatatableCellImage from '../../../../components/UI/Table/DatatableCellImage'
import { DownloadCloudIcon, Edit2Icon, Trash2Icon } from 'vue-feather-icons'
import DatatableWrapper from '../../../../components/UI/Table/DatatableWrapper'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import { mapGetters } from 'vuex'

export default {
    name: 'PlanSubscribers',
    components: {
        DatatableCellImage,
        DownloadCloudIcon,
        DatatableWrapper,
        PageTabGroup,
        MemberAvatar,
        ColorLabel,
        Trash2Icon,
        Edit2Icon,
        PageTab,
        InfoBox,
    },
    computed: {
        ...mapGetters(['config']),
        columns() {
            return {
                metered: [
                    {
                        label: this.$t('user'),
                        field: 'user_id',
                        sortable: true,
                    },
                    {
                        label: this.$t('renews_at'),
                        field: 'created_at',
                        sortable: true,
                    },
                    {
                        label: this.$t('service'),
                        field: 'driver',
                        sortable: true,
                    },
                ],
                fixed: [
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
                        field: 'ends_at',
                        sortable: true,
                    },
                    {
                        label: this.$t('service'),
                        field: 'driver',
                        sortable: true,
                    },
                ],
            }[this.config.subscriptionType]
        },
    },
    data() {
        return {
            subscribers: undefined,
            isLoading: true,
        }
    },
}
</script>
