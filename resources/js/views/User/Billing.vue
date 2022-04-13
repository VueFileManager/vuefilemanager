<template>
    <PageTab>
        <!-- Metered subscription components -->
        <div v-if="config.subscriptionType === 'metered'">
            <!--Failed Payments-->
            <UserFailedPayments />

            <!--
				...
				Charge user and increase his balance
				...
				Available for PayPal, Paystack
				...
			-->
            <UserBalance />

            <!--Usage Estimates-->
            <UserUsageEstimates />

            <!--Billing Alert-->
            <UserBillingAlerts />

            <!--
				...
				We can store user credit card and charge for fixed billing and metered billing
				This component handle storing and showing payment card UI
				...
				Handle only Stripe
				...
			-->
            <UserStoredPaymentMethods />

            <!-- Show all users transactions -->
            <UserTransactionsForMeteredBilling />
        </div>

        <!-- Fixed subscription components -->
        <div v-if="config.subscriptionType === 'fixed'">
            <!-- Empty subscription -->
            <UserEmptySubscription />

            <!-- Subscription Detail -->
            <UserFixedSubscriptionDetail />

            <!--
				...
				We can store user credit card and charge for fixed billing and metered billing
				This component handle storing and showing payment card UI
				...
				Handle only Stripe
				...
			-->
            <UserStoredPaymentMethods />

            <!--
				...
			 	Paystack or PayPal need generate external resources to update payment method.
			 	This component will handle all user cases
			 	...
			 	Handle only Paypal, Paystack
			 	...
			 -->
            <UserUpdatePaymentMethodsExternally />

            <!-- Component for cancel or upgrade subscription plan -->
            <UserEditSubscription />

            <!-- Show all users transactions -->
            <UserTransactionsForFixedBilling />
        </div>
    </PageTab>
</template>

<script>
import UserUpdatePaymentMethodsExternally from '../../components/Subscription/UserUpdatePaymentMethodsExternally'
import UserTransactionsForMeteredBilling from '../../components/Subscription/UserTransactionsForMeteredBilling'
import UserTransactionsForFixedBilling from '../../components/Subscription/UserTransactionsForFixedBilling'
import UserFixedSubscriptionDetail from '../../components/Subscription/UserFixedSubscriptionDetail'
import UserStoredPaymentMethods from '../../components/Subscription/UserStoredPaymentMethods'
import UserEmptySubscription from '../../components/Subscription/UserEmptySubscription'
import UserEditSubscription from '../../components/Subscription/UserEditSubscription'
import UserFailedPayments from '../../components/Subscription/UserFailedPayments'
import UserUsageEstimates from '../../components/Subscription/UserUsageEstimates'
import UserBillingAlerts from '../../components/Subscription/UserBillingAlerts'
import PageTab from '../../components/Layout/PageTab'
import UserBalance from '../../components/Subscription/UserBalance'
import { mapGetters } from 'vuex'

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
        ...mapGetters(['config']),
    }
}
</script>
