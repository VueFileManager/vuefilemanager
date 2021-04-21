<template>
    <section id="viewport">

		<!--File preview window-->
        <FilePreview />

		<InvoiceMobileMenu />
		<InvoiceCreateMenu />
		<InvoiceFilterMobile />
		<InvoiceSortingMobile />

		<!--Navigations-->
        <MobileNavigation />
        <SidebarNavigation />

		<!--Sidebar navigation-->
        <ContentSidebar>
            <ContentGroup title="Invoices" class="navigator menu-list-wrapper vertical">
				<a @click="goTo('invoices')" :class="{'is-active': $isThisLocation(['invoices'])}" class="menu-list-item link">
					<div class="icon text-theme">
						<file-text-icon size="17" />
					</div>
					<div class="label text-theme">
						Invoices
					</div>
				</a>
				<a @click="goTo('advance-invoices')" :class="{'is-active': $isThisLocation(['advance-invoices'])}" class="menu-list-item link">
					<div class="icon text-theme">
						<clock-icon size="17" />
					</div>
					<div class="label text-theme">
						Advance Invoices
					</div>
				</a>
            </ContentGroup>
            <ContentGroup title="Others" class="navigator menu-list-wrapper vertical">
				<a @click="goTo('clients')" :class="{'is-active': $isThisLocation(['clients'])}" class="menu-list-item link">
					<div class="icon text-theme">
						<users-icon size="17" />
					</div>
					<div class="label text-theme">
						Clients
					</div>
				</a>
            </ContentGroup>
        </ContentSidebar>

		<router-view :class="{'is-scaled-down': isScaledDown}" />
    </section>
</template>

<script>
    import InvoiceSortingMobile from '@/Oasis/Modules/Invoices/components/InvoiceSortingMobile'
	import InvoiceFilterMobile from '@/Oasis/Modules/Invoices/components/InvoiceFilterMobile'
	import InvoiceMobileMenu from '@/Oasis/Modules/Invoices/components/InvoiceMobileMenu'
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
			goTo(category) {
				let routes = {
					'invoices': 'getInvoices',
					'advance-invoices': 'getAdvanceInvoices',
					'clients': 'getClients',
				}
				this.$store.dispatch(routes[category])
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