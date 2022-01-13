<template>
    <div>
		<!--Datatable-->
		<DatatableWrapper v-if="! config.isEmptySubscriptions" @init="isLoading = false" api="/api/subscriptions/admin" :paginator="true" :columns="columns" class="card shadow-card">

			<!--Table data content-->
			<template slot-scope="{ row }">
				<tr class="border-b dark:border-opacity-5 border-light border-dashed">
					<td>
						<router-link class="flex items-center" :to="{name: 'UserDetail', params: {id: row.data.relationships.user.data.id}}">
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
						</router-link>
					</td>
					<td>
						<ColorLabel :color="$getSubscriptionStatusColor(row.data.attributes.status)">
							{{ row.data.attributes.status }}
						</ColorLabel>
					</td>
					<td class="py-5">
						<span class="text-sm font-bold capitalize text-limit" style="max-width: 160px">
							{{ row.data.attributes.name }}
						</span>
						<span class="block text-xs font-bold text-gray-400">
							{{ row.data.relationships.plan.data.attributes.price }} / {{ $t(`interval.${row.data.relationships.plan.data.attributes.interval}`) }}
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
		</DatatableWrapper>

		<!--Empty State-->
        <div class="flex items-center justify-center h-full">
			<div class="text-center">
				<img class="w-28 inline-block mb-6" src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f5c3.svg" alt="transaction">

				<h1 class="text-2xl font-bold mb-1">
					{{ $t("There is Nothing") }}
				</h1>

				<p class="text-sm text-gray-600">
					{{ $t('All your subscriptions will be visible here') }}
				</p>
			</div>
		</div>
    </div>
</template>

<script>
	import ColorLabel from "../../components/Others/ColorLabel";
	import MemberAvatar from "../../components/FilesView/MemberAvatar";
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Subscriptions',
        components: {
			ColorLabel,
			MemberAvatar,
            DatatableWrapper,
        },
        computed: {
            ...mapGetters([
				'config',
			]),
        },
        data() {
            return {
                isLoading: true,
				columns: [
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
						field: 'created_at',
						sortable: true
					},
					{
						label: this.$t('Service'),
						field: 'driver.driver',
						sortable: true
					},
				],
            }
        }
    }
</script>
