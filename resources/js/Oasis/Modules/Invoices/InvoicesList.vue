<template>
    <div @contextmenu.prevent.capture="contextMenu($event, undefined)" id="files-view">
		<DesktopToolbarInvoices/>
		<!--<ContextMenu />
        <FileBrowser/>-->
    </div>
</template>

<script>
    import DesktopToolbarInvoices from '@/Oasis/Modules/Invoices/components/DesktopToolbarInvoices'
    import FileBrowser from '@/components/FilesView/FileBrowser'
	import ContextMenu from '@/components/FilesView/ContextMenu'
    import {events} from '@/bus'

    export default {
        name: 'FilesView',
        components: {
			DesktopToolbarInvoices,
            FileBrowser,
            ContextMenu,
        },
        methods: {
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
        },
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
