<template>
    <PageTab :is-loading="isLoading">
		<DatatableWrapper @data="subscribers = $event" @init="isLoading = false" :api="`/api/subscriptions/admin/plans/${this.$route.params.id}/subscribers`" :paginator="true" :columns="columns" class="card shadow-card overflow-x-auto">

			<!--Table data content-->
			<template slot-scope="{ row }">
				<tr v-if="config.subscriptionType === 'metered'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
					<td class="py-3 md:pr-1 pr-3">
						<router-link class="flex items-center" :to="{name: 'UserDetail', params: {id: row.data.relationships.user.data.id}}">
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
						</router-link>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold whitespace-nowrap">
							{{ row.data.attributes.renews_at }}
						</span>
					</td>
					<td class="md:pl-1 pl-3 text-right">
						<img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
					</td>
				</tr>
				<tr v-if="config.subscriptionType === 'fixed'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
					<td class="py-3 md:pr-1 pr-3">
						<router-link class="flex items-center" :to="{name: 'UserDetail', params: {id: row.data.relationships.user.data.id}}">
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
						</router-link>
					</td>
					<td class="md:px-1 px-3">
						<ColorLabel :color="$getSubscriptionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
						</ColorLabel>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold capitalize text-limit" style="max-width: 160px">
							{{ row.data.attributes.name }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold">
							<!--todo: update renew attribute-->
							{{ row.data.attributes.renews_at ? row.data.attributes.renews_at : row.data.attributes.created_at }}
						</span>
					</td>
					<td class="md:px-1 px-3">
						<span class="text-sm font-bold">
							{{ row.data.attributes.ends_at ? row.data.attributes.ends_at : '-' }}
						</span>
					</td>
					<td class="md:pl-1 pl-3 text-right">
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
	import {mapGetters} from "vuex";

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
			...mapGetters([
				'config'
			]),
			columns() {
				return {
					metered: [
						{
							label: this.$t('admin_page_user.table.name'),
							field: 'user_id',
							sortable: true
						},
						{
							label: this.$t('Renews At'),
							field: 'created_at',
							sortable: true
						},
						{
							label: this.$t('Service'),
							field: 'driver',
							sortable: true
						},
					],
					fixed: [
						{
							label: this.$t('admin_page_user.table.name'),
							field: 'user_id',
							sortable: true
						},
						{
							label: this.$t('Status'),
							field: 'status',
							sortable: true
						},
						{
							label: this.$t('Note'),
							field: 'plan.name',
							sortable: true
						},
						{
							label: this.$t('Renews At'),
							field: 'created_at',
							sortable: true
						},
						{
							label: this.$t('Ends At'),
							field: 'ends_at',
							sortable: true
						},
						{
							label: this.$t('Service'),
							field: 'driver',
							sortable: true
						},
					]
				}[this.config.subscriptionType]
			}
		},
        data() {
            return {
                subscribers: undefined,
                isLoading: true,
            }
        },
    }
</script>
