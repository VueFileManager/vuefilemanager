<template>
    <div>
		<PageTab :is-loading="isLoading">
			<div class="card shadow-card">
				<DatatableWrapper
					@init="isLoading = false"
					api="/api/subscriptions/admin/transactions"
					:paginator="true"
					:columns="columns"
				>
					<template slot-scope="{ row }">
						<tr class="border-b dark:border-opacity-5 border-light border-dashed">
							<td class="py-5">
								<span class="text-sm font-bold">
									{{ row.data.attributes.note }}
								</span>
							</td>
							<td>
								<div v-if="row.data.relationships.user" class="flex items-center">
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
								<span v-if="! row.data.relationships.user" class="text-xs text-gray-500 font-bold">
									{{ $t('User was deleted') }}
								</span>
							</td>
							<td>
								<ColorLabel v-if="config.subscriptionType === 'fixed'" :color="$getTransactionStatusColor(row.data.attributes.status)">
									{{ row.data.attributes.status }}
								</ColorLabel>
								<ColorLabel v-if="config.subscriptionType === 'metered'" :color="$getTransactionTypeColor(row.data.attributes.type)">
									{{ row.data.attributes.type }}
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
				</DatatableWrapper>
			</div>
		</PageTab>
        <!--Empty invoices-->
		<!--<EmptyPageContent
                v-if="! isLoading && invoices.length === 0 && config.stripe_public_key"
                icon="file-text"
                :title="$t('admin_page_invoices.empty.title')"
                :description="$t('admin_page_invoices.empty.description')"
        >
        </EmptyPageContent>-->

        <!--Stripe Not Configured-->
		<!--<EmptyPageContent
                v-if="! config.stripe_public_key"
                icon="settings"
                :title="$t('activation.stripe.title')"
                :description="$t('activation.stripe.description')"
        >
            <router-link :to="{name: 'AppPayments'}">
                <ButtonBase button-style="theme">{{ $t('activation.stripe.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>-->

        <!--Spinner-->
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
	import MemberAvatar from "../../components/FilesView/MemberAvatar";
	import PageTab from "../../components/Others/Layout/PageTab";
    import DatatableCellImage from '/resources/js/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import EmptyPageContent from '/resources/js/components/Others/EmptyPageContent'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Invoices',
        components: {
			MemberAvatar,
            DatatableCellImage,
            MobileActionButton,
            ExternalLinkIcon,
            EmptyPageContent,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            PageHeader,
            ButtonBase,
            ColorLabel,
			PageTab,
            Spinner,
        },
        computed: {
            ...mapGetters([
				'config',
			]),
        },
        data() {
            return {
                isLoading: true,
                invoices: [],
				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('User'),
						field: 'user_id',
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
            if (! this.config.stripe_public_key)
                this.isLoading = false
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

</style>
