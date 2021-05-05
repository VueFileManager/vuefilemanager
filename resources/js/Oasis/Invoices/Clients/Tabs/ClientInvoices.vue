<template>
    <PageTab>
        <PageTabGroup>
            <DatatableWrapper
                @init="isLoading = false"
                :api="`/api/oasis/clients/${$route.params.id}/invoices`"
                :paginator="false"
                :columns="columns"
                class="table"
            >

                <!--Table data content-->
                <template slot-scope="{ row }">
                    <tr>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.invoice_number }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.total }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
								<a @click="downloadItem(row)">
									<DownloadCloudIcon size="15" class="icon" />
								</a>
                                <router-link :to="{name: 'EditInvoice', params: {id: row.data.id}}">
                                    <edit2-icon size="15" class="icon" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox class="form-fixed-width">
                        <p>Client doesn't have any invoices yet.</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {Edit2Icon, DownloadCloudIcon} from "vue-feather-icons";

    export default {
        name: 'UserInvoices',
        components: {
            EmptyPageContent,
            DatatableWrapper,
			DownloadCloudIcon,
            Edit2Icon,
            PageTabGroup,
            InfoBox,
            PageTab,
        },
        data() {
            return {
                isLoading: true,
                columns: [
                    {
                        label: this.$t('admin_page_invoices.table.number'),
                        field: 'data.attributes.order',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.total'),
                        field: 'data.attributes.bag.amount',
                        sortable: false
                    },
                    {
                        label: 'Created At',
                        field: 'data.attributes.created_at',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
		methods: {
			downloadItem(row) {
				this.$downloadFile(row.data.attributes.file_url, row.data.attributes.name + '.pdf')
			},
		}
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
