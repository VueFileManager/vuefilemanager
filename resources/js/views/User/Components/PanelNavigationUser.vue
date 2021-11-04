<template>
	<ContentSidebar>

		<!--Settings-->
		<ContentGroup title="Menu" class="navigator">
			<div class="menu-list-wrapper vertical">
				<router-link replace :to="{name: 'Profile'}" class="menu-list-item link">
					<div class="icon text-theme">
						<user-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.profile') }}
					</div>
				</router-link>

				<router-link replace :to="{name: 'Storage'}" class="menu-list-item link">
					<div class="icon text-theme">
						<hard-drive-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.storage') }}
					</div>
				</router-link>

				<router-link replace :to="{name: 'Password'}" class="menu-list-item link">
					<div class="icon text-theme">
						<lock-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.password') }}
					</div>
				</router-link>
			</div>
		</ContentGroup>

		<!--Subscription-->
		<ContentGroup v-if="canShowSubscriptionSettings" :title="$t('user_subscription.title')" class="navigator">
			<div class="menu-list-wrapper vertical">
				<router-link replace :to="{name: 'Subscription'}" class="menu-list-item link">
					<div class="icon text-theme">
						<cloud-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.subscription') }}
					</div>
				</router-link>

				<router-link replace :to="{name: 'PaymentMethods'}" class="menu-list-item link">
					<div class="icon text-theme">
						<credit-card-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.payment_cards') }}
					</div>
				</router-link>

				<router-link replace :to="{name: 'Invoice'}" class="menu-list-item link">
					<div class="icon text-theme">
						<file-text-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('menu.invoices') }}
					</div>
				</router-link>
			</div>
		</ContentGroup>
	</ContentSidebar>
</template>

<script>
    import ContentSidebar from '/resources/js/components/Sidebar/ContentSidebar'
	import ContentGroup from '/resources/js/components/Sidebar/ContentGroup'
	import {mapGetters} from "vuex"
	import {
		CreditCardIcon,
		HardDriveIcon,
		FileTextIcon,
		CloudIcon,
		UserIcon,
		LockIcon,
		KeyIcon,
	} from 'vue-feather-icons'

	export default {
		name: "PanelNavigationUser",
		components: {
			ContentSidebar,
			CreditCardIcon,
			ContentGroup,
			HardDriveIcon,
			FileTextIcon,
			CloudIcon,
			UserIcon,
			LockIcon,
			KeyIcon,
		},
		computed: {
			...mapGetters([
				'config',
				'user',
			]),
			canShowSubscriptionSettings() {
				return this.config.isSaaS && this.config.app_payments_active
			},
		}
	}
</script>

<style scoped>

</style>