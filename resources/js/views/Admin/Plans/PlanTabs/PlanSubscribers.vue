<template>
    <PageTab :is-loading="isLoading">
		<DatatableWrapper @data="subscribers = $event" @init="isLoading = false" :api="`/api/subscription/plans/${this.$route.params.id}/subscribers`" :paginator="true" :columns="columns" class="card shadow-card">

			<!--Table data content-->
			<template slot-scope="{ row }">
				<tr class="border-b dark:border-opacity-5 border-light border-dashed">
					<td>
						<div class="flex items-center">
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
					</td>
					<td>
						<ColorLabel :color="getSubscriptionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
						</ColorLabel>
					</td>
					<td class="py-5">
						<span class="text-sm font-bold capitalize text-limit" style="max-width: 160px">
							{{ row.data.attributes.name }}
						</span>
					</td>
					<td>
						<span class="text-sm font-bold">
							<!--todo: update renew attribute-->
							{{ row.data.attributes.renews_at ? row.data.attributes.renews_at : row.data.attributes.created_at }}
						</span>
					</td>
					<td>
						<span class="text-sm font-bold">
							{{ row.data.attributes.ends_at ? row.data.attributes.ends_at : '-' }}
						</span>
					</td>
					<td class="text-right">
						<img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
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
	import ColorLabel from "../../../../components/Others/ColorLabel";
	import MemberAvatar from "../../../../components/FilesView/MemberAvatar";
    import DatatableCellImage from '/resources/js/components/Others/Tables/DatatableCellImage'
    import {DownloadCloudIcon, Edit2Icon, Trash2Icon} from "vue-feather-icons"
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import axios from 'axios'

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
        data() {
            return {
                subscribers: undefined,
                isLoading: true,
                columns: [
                    {
                        label: this.$t('admin_page_user.table.name'),
                        field: 'name',
                        sortable: true
                    },
                    {
                        label: this.$t('Status'),
                        field: 'used',
                        sortable: false
                    },
                    {
                        label: this.$t('Plan'),
                        field: 'name',
                        sortable: true
                    },
                    {
                        label: this.$t('Renews At'),
                        sortable: false
                    },
                    {
                        label: this.$t('Ends At'),
                        sortable: false
                    },
                    {
                        label: this.$t('Service'),
                        sortable: false
                    },
                ],
            }
        },
    }
</script>
