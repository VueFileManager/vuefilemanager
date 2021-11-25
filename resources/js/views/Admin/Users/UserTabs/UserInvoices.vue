<template>
    <PageTab :is-loading="isLoading">
		<div class="card shadow-card">
			<DatatableWrapper
				@init="isLoading = false"
				:api="'/api/subscriptions/users/' + this.$route.params.id + '/transactions'"
				:paginator="true"
				:columns="columns"
			>
                <template slot-scope="{ row }">
                    <tr class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td class="py-4">
                            <span class="text-sm font-bold">
								{{ row.data.attributes.plan_name }}
							</span>
                        </td>
                        <td>
							<ColorLabel color="purple">
                                {{ row.data.attributes.status }}
							</ColorLabel>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.price }}
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
    </PageTab>
</template>

<script>
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
	import ColorLabel from "/resources/js/components/Others/ColorLabel"
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'

    export default {
        name: 'UserInvoices',
        components: {
            DatatableWrapper,
			ColorLabel,
            InfoBox,
            PageTab,
        },
        data() {
            return {
                isLoading: true,
                columns: [
                    {
                        label: this.$t('Plan'),
                        field: 'plan_name',
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
                        sortable: true
                    },
                ],
            }
        },
    }
</script>
