<template>
    <PageTab>
		<!-- Metered subscription components -->
		<div v-if="config.subscriptionType === 'metered'">
			<!--Failed Payments-->
			<UserFailedPayments />

			<!--Balance-->
			<UserBalance/>

			<!--Usage Estimates-->
			<UserUsageEstimates />

			<!--Billing Alert-->
			<UserBillingAlerts />

			<!-- Payment method for automatically handle payments - only for Stripe -->
			<UserStoredPaymentMethods />

			<!--Transactions-->
			<UserTransactionsForMeteredBilling />
		</div>

		<!-- Fixed subscription components -->
		<div v-if="config.subscriptionType === 'fixed'">

			<!-- Subscription Detail -->
			<UserFixedSubscriptionDetail />

			<!-- Payment method for automatically handle payments - only for Stripe -->
			<UserStoredPaymentMethods />

			<!-- Update payment in external source -->
			<UserUpdatePaymentMethodsExternally />

			<!-- Edit subscription -->
			<UserEditSubscription />

			<!-- Transactions -->
			<UserTransactionsForFixedBilling />

			<!-- Empty subscription -->
			<UserEmptySubscription />
		</div>

    </PageTab>
</template>

<script>
	import UserUpdatePaymentMethodsExternally from "../../components/Subscription/UserUpdatePaymentMethodsExternally"
	import UserTransactionsForMeteredBilling from "../../components/Subscription/UserTransactionsForMeteredBilling"
	import UserTransactionsForFixedBilling from "../../components/Subscription/UserTransactionsForFixedBilling"
	import UserFixedSubscriptionDetail from "../../components/Subscription/UserFixedSubscriptionDetail"
	import UserStoredPaymentMethods from "../../components/Subscription/UserStoredPaymentMethods"
	import UserEmptySubscription from "../../components/Subscription/UserEmptySubscription"
	import UserEditSubscription from "../../components/Subscription/UserEditSubscription"
	import UserFailedPayments from "../../components/Subscription/UserFailedPayments"
	import UserUsageEstimates from "../../components/Subscription/UserUsageEstimates"
	import UserBillingAlerts from "../../components/Subscription/UserBillingAlerts"
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import UserBalance from "../../components/Subscription/UserBalance"
	import {mapGetters} from 'vuex'

	export default {
		name: 'Billing',
		components: {
			UserUpdatePaymentMethodsExternally,
			UserTransactionsForMeteredBilling,
			UserTransactionsForFixedBilling,
			UserFixedSubscriptionDetail,
			UserStoredPaymentMethods,
			UserEmptySubscription,
			UserEditSubscription,
			UserFailedPayments,
			UserUsageEstimates,
			UserBillingAlerts,
			UserBalance,
			PageTab,
		},
		computed: {
			...mapGetters([
				'config',
			]),
		},
	}
</script>
