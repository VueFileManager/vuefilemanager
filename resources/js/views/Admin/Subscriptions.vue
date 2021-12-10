<template>
    <div>
		<!--Datatable-->
		<DatatableWrapper @init="isLoading = false" api="/api/subscriptions/admin" :paginator="true" :columns="columns" class="card shadow-card">

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

			<!--Empty page-->
			<template v-slot:empty-page>
				<InfoBox style="margin-bottom: 0">
					<p>{{ $t('admin_page_plans.subscribers.empty') }}</p>
				</InfoBox>
			</template>
		</DatatableWrapper>

        <!--Stripe configured but has empty plans-->
		<!--<EmptyPageContent
                v-if="isEmptyPlans"
                icon="file"
                :title="$t('admin_page_plans.empty.title')"
                :description="$t('admin_page_plans.empty.description')"
        >
            <router-link :to="{name: 'PlanCreate'}" tag="p">
                <ButtonBase button-style="theme">{{ $t('admin_page_plans.empty.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>-->

        <!--Stripe is Not Configured-->
		<!--<EmptyPageContent
                v-if="stripeIsNotConfigured"
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
	import InfoBox from "../../components/Others/Forms/InfoBox";
	import ColorLabel from "../../components/Others/ColorLabel";
	import MemberAvatar from "../../components/FilesView/MemberAvatar";
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import EmptyPageContent from '/resources/js/components/Others/EmptyPageContent'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Subscriptions',
        components: {
			InfoBox,
			ColorLabel,
			MemberAvatar,
            EmptyPageContent,
            DatatableWrapper,
            SwitchInput,
            ButtonBase,
            Spinner,
        },
        computed: {
            ...mapGetters([
				'config'
			]),
            stripeIsNotConfigured() {
                return ! this.config.stripe_public_key
            },
            stripeConfiguredWithPlans() {
                return ! this.isLoading && this.config.stripe_public_key
            }
        },
        data() {
            return {
                isLoading: true,
                plans: [],
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
        },
        created() {
            if (! this.config.stripe_public_key)
                this.isLoading = false
        }
    }
</script>
