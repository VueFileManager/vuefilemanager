<template>
    <div @click="fileViewClick"
         @contextmenu.prevent.capture="contextMenu($event, undefined)"
         id="files-view">
        <ContextMenu/>
        <DesktopToolbar/>
        <FileBrowser/>
    </div>
</template>

<script>
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
    import FileBrowser from '@/components/FilesView/FileBrowser'
    import ContextMenu from '@/components/FilesView/ContextMenu'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilesView',
        components: {
            DesktopToolbar,
            FileBrowser,
            ContextMenu,
        },
        computed: {
            ...mapGetters(['config']),
        },
        methods: {
            fileViewClick() {
                events.$emit('contextMenu:hide')
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
        },
    }
</script>

<style lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
