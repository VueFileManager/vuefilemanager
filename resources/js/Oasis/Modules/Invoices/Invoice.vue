<template>
    <div id="application-wrapper">

		<!--File preview window-->
        <FilePreview />

		<InvoiceMobileMenu />
		<ClientMobileMenu />
		<InvoiceCreateMenu />
		<InvoiceFilterMobile />
		<InvoiceSortingMobile />

		<!--Navigations-->
        <MobileNavigation />
        <SidebarNavigation />

		<router-view :class="{'is-scaled-down': isScaledDown}" />
    </div>
</template>

<script>
    import InvoiceSortingMobile from '@/Oasis/Modules/Invoices/components/InvoiceSortingMobile'
	import InvoiceFilterMobile from '@/Oasis/Modules/Invoices/components/InvoiceFilterMobile'
	import InvoiceMobileMenu from '@/Oasis/Modules/Invoices/components/InvoiceMobileMenu'
	import ClientMobileMenu from '@/Oasis/Modules/Invoices/components/ClientMobileMenu'
	import InvoiceCreateMenu from '@/Oasis/Modules/Invoices/components/InvoiceCreateMenu'

	import {UsersIcon, FileTextIcon, ClockIcon} from 'vue-feather-icons'
	import SidebarNavigation from '@/components/Sidebar/SidebarNavigation'
	import MobileNavigation from '@/components/Others/MobileNavigation'
	import ContentSidebar from '@/components/Sidebar/ContentSidebar'
	import FilePreview from '@/components/FilesView/FilePreview'
	import ContentGroup from '@/components/Sidebar/ContentGroup'
	import {mapGetters} from 'vuex'
	import {events} from '@/bus'

	export default {
		name: 'Settings',
		computed: {
			...mapGetters([
				'config'
			]),
		},
		components: {
			InvoiceSortingMobile,
			InvoiceFilterMobile,
			InvoiceCreateMenu,
			InvoiceMobileMenu,
			SidebarNavigation,
			ClientMobileMenu,
			MobileNavigation,
			ContentSidebar,
			FileTextIcon,
			ContentGroup,
			FilePreview,
			UsersIcon,
			ClockIcon,
		},
		data() {
			return {
				isScaledDown: false,
			}
		},
		methods: {
			goTo(location) {
				let routes = {
					'regular-invoice': 'getRegularInvoices',
					'advance-invoice': 'getAdvanceInvoices',
					'clients': 'getClients',
				}
				this.$store.dispatch(routes[location])
			}
		},
		mounted() {
			events.$on('mobile-menu:show', () => this.isScaledDown = true)

			events.$on('fileItem:deselect', () => this.isScaledDown = false)
			events.$on('mobile-menu:hide', () => this.isScaledDown = false)
		}
	}
</script>

<style lang="scss">
    @import '@assets/vuefilemanager/_mixins';

	@media only screen and (max-width: 690px) {

		.is-scaled-down {
			@include transform(scale(0.95));
		}
	}
</style>