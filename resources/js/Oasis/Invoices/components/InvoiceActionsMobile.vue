<template>
    <div id="mobile-actions-wrapper">

        <!--Base location-->
        <div class="mobile-actions">
            <MobileActionButton @click.native="showLocations" icon="filter">
                {{ directoryName }}
            </MobileActionButton>
            <MobileActionButton @click.native="createButton" icon="file-plus">
                {{ $t('create') }}
            </MobileActionButton>
            <MobileActionButton @click.native="showViewOptions" icon="th-list">
                {{ $t('in.button_sorting') }}
            </MobileActionButton>
        </div>
    </div>
</template>

<script>
    import MobileActionButtonUpload from '@/components/FilesView/MobileActionButtonUpload'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import UploadProgress from '@/components/FilesView/UploadProgress'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'InvoiceActionsMobile',
        components: {
            MobileActionButtonUpload,
            MobileActionButton,
            UploadProgress,
        },
        computed: {
            ...mapGetters([
                'currentFolder'
            ]),
			directoryName() {
				return this.currentFolder
					? this.currentFolder.name
					: this.$t('in.nav.invoices')
			},
        },
        data() {
            return {
                isSelectMode: false,
            }
        },
        methods: {
            showLocations() {
                events.$emit('mobile-menu:show', 'invoice-filter')
            },
			createButton() {
                events.$emit('mobile-menu:show', 'invoice-create')
            },
			showViewOptions() {
				events.$emit('mobile-menu:show', 'invoice-sorting')
			},
            selectAll() {
                this.$store.commit('ADD_ALL_ITEMS_TO_CLIPBOARD')
            },
            deselectAll() {
                this.$store.commit('CLIPBOARD_CLEAR')
            },
            enableMultiSelectMode() {
                this.isSelectMode = true

                events.$emit('mobileSelecting:start')
            },
            disableMultiSelectMode() {
                this.isSelectMode = false

                events.$emit('mobileSelecting:stop')
            },
        },
        mounted() {
            events.$on('mobileSelecting:stop', () => this.isSelectMode = false)
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .button-enter-active,
    .button-leave-active {
        transition: all 250ms;
    }

    .button-enter {
        opacity: 0;
        transform: translateY(-50%);
    }

    .button-leave-to {
        opacity: 0;
        transform: translateY(50%);
    }

    .button-leave-active {
        position: absolute;
    }

    #mobile-actions-wrapper {
        display: none;
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-actions {
        white-space: nowrap;
        overflow-x: auto;
        margin: 0 -15px;
        padding: 10px 0 10px 15px;
    }

    @media only screen and (max-width: 960px) {

        #mobile-actions-wrapper {
            display: block;
        }
    }

    @media (prefers-color-scheme: dark) {
        #mobile-actions-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
