<template>
    <div id="application-wrapper">

		<!--File preview window-->
        <FilePreview />

		<!--Popups-->
		<ConfirmPopup />
		<ShareInvoicePopup />

		<InvoiceMobileMenu />
		<ClientMobileMenu />
		<InvoiceCreateMenu />
		<InvoiceFilterMobile />
		<InvoiceSortingMobile />

		<!--Navigations-->
        <MobileNavigation />
        <SidebarNavigation />

		<div id="viewport" :class="{'is-scaled-down': isScaledDown}">

			<!--Sidebar navigation-->
			<ContentSidebar>
				<ContentGroup :title="$t('in.nav.group.invoicing')" class="navigator menu-list-wrapper vertical">
					<a @click="goTo('regular-invoice')" :class="{'is-active': $isThisLocation(['regular-invoice']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<file-text-icon size="17" />
						</div>
						<div class="label text-theme">
							{{ $t('in.nav.invoices') }}
						</div>
					</a>
					<a @click="goTo('advance-invoice')" :class="{'is-active': $isThisLocation(['advance-invoice']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<clock-icon size="17" />
						</div>
						<div class="label text-theme">
							{{ $t('in.nav.advance_invoices') }}
						</div>
					</a>
					<a @click="goTo('clients')" :class="{'is-active': $isThisLocation(['clients']) && $route.name === 'InvoicesList'}" class="menu-list-item link">
						<div class="icon text-theme">
							<users-icon size="17" />
						</div>
						<div class="label text-theme">
							{{ $t('in.nav.clients') }}
						</div>
					</a>
				</ContentGroup>
				<ContentGroup :title="$t('in.nav.group.settings')" class="navigator menu-list-wrapper vertical">
					<router-link :to="{name: 'BillingProfile'}" class="menu-list-item link">
						<div class="icon text-theme">
							<edit2-icon size="17" />
						</div>
						<div class="label text-theme">
							{{ $t('in.nav.my_bill_profile') }}
						</div>
					</router-link>
				</ContentGroup>
			</ContentSidebar>

			<router-view />
		</div>
    </div>
</template>

<script>
    import InvoiceSortingMobile from '@/Oasis/Invoices/components/InvoiceSortingMobile'
	import InvoiceFilterMobile from '@/Oasis/Invoices/components/InvoiceFilterMobile'
    import ShareInvoicePopup from '@/Oasis/Invoices/components/ShareInvoicePopup'
	import InvoiceMobileMenu from '@/Oasis/Invoices/components/InvoiceMobileMenu'
	import InvoiceCreateMenu from '@/Oasis/Invoices/components/InvoiceCreateMenu'
	import ClientMobileMenu from '@/Oasis/Invoices/components/ClientMobileMenu'
	import ConfirmPopup from '@/components/Others/Popup/ConfirmPopup'

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
				'currentFolder',
				'config',
			]),
		},
		components: {
			InvoiceSortingMobile,
			InvoiceFilterMobile,
			InvoiceCreateMenu,
			ShareInvoicePopup,
			InvoiceMobileMenu,
			SidebarNavigation,
			ClientMobileMenu,
			MobileNavigation,
			ContentSidebar,
			ConfirmPopup,
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

			if (! this.currentFolder) {

				this.$store.commit('STORE_CURRENT_FOLDER', {
					name: this.$t('in.nav.invoices'),
					id: undefined,
					location: 'regular-invoice',
				})

				this.$store.dispatch('getRegularInvoices')
			}

			events.$on('mobile-menu:show', () => this.isScaledDown = true)

			events.$on('fileItem:deselect', () => this.isScaledDown = false)
			events.$on('mobile-menu:hide', () => this.isScaledDown = false)

			events.$on('action:confirmed', data => {
				if (data.operation === 'delete-invoice') {

					axios.delete(`/api/v1/invoicing/invoices/${data.id}`)
						.then(() => this.goTo(this.currentFolder.location))
						.catch(() => this.$isSomethingWrong())
				}
			})

			events.$on('action:confirmed', data => {
				if (data.operation === 'delete-client') {

					axios.delete(`/api/v1/invoicing/clients/${data.id}`)
						.then(() => this.goTo(this.currentFolder.location))
						.catch(() => this.$isSomethingWrong())
				}
			})
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