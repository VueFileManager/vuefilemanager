<template>
    <div>
		<!--Page Tab links-->
		<div class="card shadow-card z-10" style="padding-bottom: 0; padding-top: 0;">
			<CardNavigation :pages="pages" class="-mx-1" />
		</div>

		<!--Page Content-->
		<router-view />
    </div>
</template>

<script>
	import CardNavigation from "../../../components/Admin/CardNavigation";

	export default {
		name: 'PaymentSettings',
		components: {
			CardNavigation,
		},
		data() {
			return {
				pages: [
					{
						title: this.$t('admin_settings.tabs.payments'),
						route: 'AppPayments',
					},
					{
						title: this.$t('admin_settings.tabs.billings'),
						route: 'AppBillings',
					},
				]
			}
		},
		mounted() {
			this.$router.replace({name: 'AppPayments'})
		}
	}
</script>
