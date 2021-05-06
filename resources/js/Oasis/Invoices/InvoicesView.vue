<template>
    <div @contextmenu.prevent.capture="contextMenu($event, undefined)" id="files-view">
		<InvoiceDesktopToolbar />
		<InvoiceContextMenu />
        <InvoiceBrowser />
    </div>
</template>

<script>
    import InvoiceDesktopToolbar from '@/Oasis/Invoices/components/InvoiceDesktopToolbar'
	import InvoiceBrowser from '@/Oasis/Invoices/components/InvoiceBrowser'
	import InvoiceContextMenu from '@/Oasis/Invoices/components/InvoiceContextMenu'
	import {events} from '@/bus'

	export default {
		name: 'ContentInvoiceView',
		components: {
			InvoiceDesktopToolbar,
			InvoiceContextMenu,
			InvoiceBrowser,
		},
		methods: {
			contextMenu(event, item) {
				events.$emit('contextMenu:show', event, item)
			},
		},
		mounted() {

		}
	}
</script>

<style lang="scss">

    #files-view {
		font-family: 'Nunito', sans-serif;
		font-size: 16px;
		width: 100%;
		height: 100%;
		position: relative;
		min-width: 320px;
		overflow-x: hidden;
		padding-left: 15px;
		padding-right: 15px;
		overflow-y: hidden;
	}

	@media only screen and (max-width: 690px) {
		#files-view {
			padding-left: 0;
			padding-right: 0;
		}
	}

</style>
