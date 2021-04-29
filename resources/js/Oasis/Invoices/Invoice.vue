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

		<div id="viewport">

			<!--Sidebar navigation-->
			<ContentSidebar>
				<ContentGroup title="Invoices" class="navigator menu-list-wrapper vertical">
					<a @click="goTo('regular-invoice')" :class="{'is-active': $isThisLocation(['regular-invoice']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<file-text-icon size="17" />
						</div>
						<div class="label text-theme">
							Invoices
						</div>
					</a>
					<a @click="goTo('advance-invoice')" :class="{'is-active': $isThisLocation(['advance-invoice']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<clock-icon size="17" />
						</div>
						<div class="label text-theme">
							Advance Invoices
						</div>
					</a>
				</ContentGroup>
				<ContentGroup title="Others" class="navigator menu-list-wrapper vertical">
					<a @click="goTo('clients')" :class="{'is-active': $isThisLocation(['clients']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<users-icon size="17" />
						</div>
						<div class="label text-theme">
							Clients
						</div>
					</a>
					<router-link :to="{name: 'InvoicesProfile'}" class="menu-list-item link">
						<div class="icon text-theme">
							<edit2-icon size="17" />
						</div>
						<div class="label text-theme">
							My Billing Profile
						</div>
					</router-link>
				</ContentGroup>
			</ContentSidebar>

			<keep-alive>
				<router-view :class="{'is-scaled-down': isScaledDown}" />
			</keep-alive>
		</div>
    </div>
</template>

<script>
    import InvoiceSortingMobile from '@/Oasis/Invoices/components/InvoiceSortingMobile'
	import InvoiceFilterMobile from '@/Oasis/Invoices/components/InvoiceFilterMobile'
	import InvoiceMobileMenu from '@/Oasis/Invoices/components/InvoiceMobileMenu'
	import ClientMobileMenu from '@/Oasis/Invoices/components/ClientMobileMenu'
	import InvoiceCreateMenu from '@/Oasis/Invoices/components/InvoiceCreateMenu'

	import {UsersIcon, FileTextIcon, ClockIcon, Edit2Icon} from 'vue-feather-icons'
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
			Edit2Icon,
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

				if (this.$route.name !== 'InvoicesList') {
					this.$router.push({name: 'InvoicesList'})
				}

				this.$store.dispatch(routes[location])
			},
		},
		mounted() {
			events.$on('mobile-menu:show', () => this.isScaledDown = true)

			events.$on('fileItem:deselect', () => this.isScaledDown = false)
			events.$on('mobile-menu:hide', () => this.isScaledDown = false)

			this.$store.dispatch('getRegularInvoices')
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