<template>
    <PageTab :is-loading="isLoading">

		<UserMeteredSubscription
			v-if="subscription && config.subscriptionType === 'metered'"
			:subscription="subscription"
			:user="user"
		/>

		<UserFixedSubscription
			v-if="subscription && config.subscriptionType === 'fixed'"
			:subscription="subscription"
			:user="user"
		/>

		<!--Transactions-->
		<div class="card shadow-card">
			<FormLabel icon="file-text">
                {{ $t('Transactions') }}
            </FormLabel>

			<DatatableWrapper
				@init="isLoading = false"
				:api="'/api/subscriptions/admin/users/' + this.$route.params.id + '/transactions'"
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

		<!--Empty Message-->
		<div v-if="! subscription && !isLoading" class="card shadow-card">
			<InfoBox style="margin-bottom: 0">
				<p>{{ $t("User don't have any subscription") }}</p>
			</InfoBox>
		</div>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
	import FormLabel from "../../../../components/Others/Forms/FormLabel";
	import ColorLabel from "/resources/js/components/Others/ColorLabel"
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import UserMeteredSubscription from "./UserMeteredSubscription";
	import UserFixedSubscription from "./UserFixedSubscription"
	import {mapGetters} from "vuex";
	import axios from 'axios'

	export default {
		name: 'UserSubscription',
		props: [
			'user'
		],
		components: {
			UserMeteredSubscription,
			UserFixedSubscription,
			DatatableWrapper,
			ColorLabel,
			FormLabel,
			InfoBox,
			PageTab,
		},
		computed: {
			...mapGetters([
				'config',
			]),
		},
		data() {
			return {
				subscription: undefined,
				isLoading: true,
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
		},
		created() {
			axios.get(`/api/subscriptions/admin/users/${this.$route.params.id}/subscription`)
				.then(response => {
					this.subscription = response.data.data

					this.isLoading = false
				})
				.catch(error => {
					if (error.response.status === 404)
						this.isLoading = false
				})
		}
	}
</script>
